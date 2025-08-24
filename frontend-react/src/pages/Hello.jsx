import React, { useEffect, useState } from "react";
import axios from "axios";

const Hello = () => {
  const [listings, setListings] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchListings = async () => {
      try {
        const res = await axios.get("http://localhost:8000/api/v1/listings");
        // Check if the response structure matches your API
        // If the data is directly in res.data (array), use:
        setListings(res.data || []);
        
        // If the data is nested like { data: [...] }, use:
        // setListings(res.data.data || []);
      } catch (err) {
        setError(err.response?.data?.message || err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchListings();
  }, []);

  return (
    <main className="flex items-center justify-center min-h-screen bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500">
      <div className="bg-white shadow-2xl rounded-2xl p-10 text-center max-w-2xl w-full">
        <h1 className="text-4xl font-bold text-gray-800 mb-4">👋 Hello, Welcome!</h1>

        {loading && <p className="text-gray-500">Loading listings...</p>}
        {error && <p className="text-red-500">{error}</p>}

        {!loading && !error && (
          <ul className="space-y-4 text-left">
            {listings.length > 0 ? (
              listings.map((listing) => (
                <li
                  key={listing.id}
                  className="p-4 border rounded-xl shadow hover:shadow-lg transition bg-gray-50"
                >
                  <h2 className="text-xl font-semibold text-indigo-600">{listing.address}</h2>
                  <p className="text-gray-600">
                    {listing.bedrooms} 🛏 | {listing.bathrooms} 🛁 | 
                    {listing.parking ? " 🅿️ Parking" : ""} | 
                    {listing.furnished ? " 🪑 Furnished" : ""}
                  </p>
                  <p className="text-gray-800 font-bold mt-2">
                    ${listing.type === 'rent' ? listing.regularPrice + '/month' : listing.regularPrice}
                    {listing.offer && listing.discountedPrice && (
                      <span className="ml-2 text-green-600 line-through">
                        ${listing.discountedPrice}
                      </span>
                    )}
                  </p>
                  <p className="text-sm text-gray-500 capitalize">{listing.type}</p>
                </li>
              ))
            ) : (
              <p className="text-gray-500">No listings found.</p>
            )}
          </ul>
        )}
      </div>
    </main>
  );
};

export default Hello;