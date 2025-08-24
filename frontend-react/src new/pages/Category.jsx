import { useEffect, useState } from "react";
import { toast } from "react-toastify";
import "./Offers.scss";
import ListingItem from "../components/ListingItem";
import { useParams } from "react-router-dom";
import axios from "axios";
import Spinner from "../components/Spinner";

const Category = () => {
  const [listings, setListings] = useState(null);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [hasMore, setHasMore] = useState(true);
  const params = useParams();

  useEffect(() => {
    async function fetchListings() {
      try {
        const response = await axios.get(
          `localhost:8000/api/listings?type=${params.categoryName}&page=${page}&limit=8`
        );
        setListings(prev => prev ? [...prev, ...response.data.data] : response.data.data);
        setHasMore(response.data.meta.current_page < response.data.meta.last_page);
        setLoading(false);
      } catch (error) {
        toast.error("Could not fetch listings");
        setLoading(false);
      }
    }

    fetchListings();
  }, [params.categoryName, page]);

  if (loading && !listings) {
    return <Spinner />;
  }

  return (
    <div className="offer">
      <h1 className="offer__title">
        {params.categoryName === "rent" ? "Places for rent" : "Places for sale"}
      </h1>
      {listings && listings.length > 0 ? (
        <>
          <main>
            <ul className="offer__listing">
              {listings.map((listing) => (
                <ListingItem
                  key={listing.id}
                  id={listing.id}
                  listing={listing}
                />
              ))}
            </ul>
          </main>
          {hasMore && (
            <div className="offer__load-more-btn-wrap">
              <button
                onClick={() => setPage(prev => prev + 1)}
                className="offer__load-more-btn"
                disabled={loading}
              >
                {loading ? 'Loading...' : 'Load more'}
              </button>
            </div>
          )}
        </>
      ) : (
        <p>
          There are no current{" "}
          {params.categoryName === "rent"
            ? "Places for rent"
            : "Places for sale"}
        </p>
      )}
    </div>
  );
};

export default Category;