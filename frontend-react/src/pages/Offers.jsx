import React, { useState, useEffect } from "react";
import { listingsAPI } from "../services/api";
import ListingItem from "../components/ListingItem";
import Spinner from "../components/Spinner";
import "./Offers.scss";

const Offers = () => {
  const [listings, setListings] = useState([]);
  const [loading, setLoading] = useState(true);
  const [filter, setFilter] = useState("all"); // "all", "rent", "sale"
  const [sortBy, setSortBy] = useState("newest"); // "newest", "price-low", "price-high"

  useEffect(() => {
    async function fetchListings() {
      try {
        const response = await listingsAPI.getWithFilters({ offer: true });
        const listingsData = response.data.data || response.data;
        setListings(listingsData);
      } catch (error) {
        console.error("Error fetching listings:", error);
      } finally {
        setLoading(false);
      }
    }
    fetchListings();
  }, []);

  // Filter and sort listings
  const filteredListings = listings
    .filter(listing => {
      if (filter === "all") return true;
      return listing.type === filter;
    })
    .sort((a, b) => {
      if (sortBy === "newest") {
        return new Date(b.created_at) - new Date(a.created_at);
      } else if (sortBy === "price-low") {
        return parseFloat(a.discountedPrice || a.regularPrice) - parseFloat(b.discountedPrice || b.regularPrice);
      } else if (sortBy === "price-high") {
        return parseFloat(b.discountedPrice || b.regularPrice) - parseFloat(a.discountedPrice || a.regularPrice);
      }
      return 0;
    });

  if (loading) {
    return <Spinner />;
  }

  return (
    <div className="offers-page">
      <div className="offers-hero">
        <div className="hero-content">
          <h1>Special Offers</h1>
          <p>Discover exclusive deals on premium properties</p>
        </div>
        <div className="hero-gradient"></div>
      </div>

      <div className="offers-container">
        <div className="offers-header">
          <div className="results-info">
            <h2>Available Offers</h2>
            <p>{filteredListings.length} property{filteredListings.length !== 1 ? 's' : ''} found</p>
          </div>
          
          <div className="controls">
            <div className="filter-control">
              <label>Filter by:</label>
              <select value={filter} onChange={(e) => setFilter(e.target.value)}>
                <option value="all">All Types</option>
                <option value="rent">For Rent</option>
                <option value="sale">For Sale</option>
              </select>
            </div>
            
            <div className="sort-control">
              <label>Sort by:</label>
              <select value={sortBy} onChange={(e) => setSortBy(e.target.value)}>
                <option value="newest">Newest</option>
                <option value="price-low">Price: Low to High</option>
                <option value="price-high">Price: High to Low</option>
              </select>
            </div>
          </div>
        </div>

        {filteredListings.length > 0 ? (
          <div className="offers-grid">
            {filteredListings.map((listing) => (
              <ListingItem
                key={listing.id}
                listing={listing}
                id={listing.id}
              />
            ))}
          </div>
        ) : (
          <div className="no-offers">
            <div className="no-offers-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
              </svg>
            </div>
            <h3>No offers available</h3>
            <p>There are currently no special offers matching your criteria.</p>
            <button 
              className="reset-filters-btn"
              onClick={() => {
                setFilter("all");
                setSortBy("newest");
              }}
            >
              Reset Filters
            </button>
          </div>
        )}
      </div>
    </div>
  );
};

export default Offers;