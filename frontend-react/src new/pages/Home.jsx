import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import ListingItem from "../components/ListingItem";
import Slider from "../components/Slider";
import api from "../api"; // Import the configured api instance
import "./Home.scss";
import Spinner from "../components/Spinner";

export default function Home() {
  const [offerListings, setOfferListings] = useState(null);
  const [rentListings, setRentListings] = useState(null);
  const [saleListings, setSaleListings] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function fetchListings() {
      try {
        const [offers, rent, sale] = await Promise.all([
          api.get('/listings', { params: { offer: true, limit: 3 } }),
          api.get('/listings', { params: { type: 'rent', limit: 3 } }),
          api.get('/listings', { params: { type: 'sale', limit: 3 } })
        ]);
        
        setOfferListings(offers.data.data);
        setRentListings(rent.data.data);
        setSaleListings(sale.data.data);
      } catch (error) {
        console.error("Error fetching listings:", error);
      } finally {
        setLoading(false);
      }
    }
    
    fetchListings();
  }, []);

  if (loading) {
    return <Spinner />;
  }

  return (
    <div className="home-page">
      <Slider />
      <div className="home-page__listing">
        {offerListings && offerListings.length > 0 && (
          <div className="home-page__listing-category-wrap">
            <h2 className="home-page__listing-category-title">Recent offers</h2>
            <Link to="/offers">
              <p className="home-page__listing-category-link">
                Show more offers
              </p>
            </Link>
            <ul className="home-page__listing-category-list">
              {offerListings.map((listing) => (
                <ListingItem
                  key={listing.id}
                  listing={listing}
                  id={listing.id}
                />
              ))}
            </ul>
          </div>
        )}
        {rentListings && rentListings.length > 0 && (
          <div className="home-page__listing-category-wrap">
            <h2 className="home-page__listing-category-title">
              Places for rent
            </h2>
            <Link to="/category/rent">
              <p className="home-page__listing-category-link">
                Show more places for rent
              </p>
            </Link>
            <ul className="home-page__listing-category-list">
              {rentListings.map((listing) => (
                <ListingItem
                  key={listing.id}
                  listing={listing}
                  id={listing.id}
                />
              ))}
            </ul>
          </div>
        )}
        {saleListings && saleListings.length > 0 && (
          <div className="home-page__listing-category-wrap">
            <h2 className="home-page__listing-category-title">
              Places for sale
            </h2>
            <Link to="/category/sale">
              <p className="home-page__listing-category-link">
                Show more places for sale
              </p>
            </Link>
            <ul className="home-page__listing-category-list">
              {saleListings.map((listing) => (
                <ListingItem
                  key={listing.id}
                  listing={listing}
                  id={listing.id}
                />
              ))}
            </ul>
          </div>
        )}
      </div>
    </div>
  );
}