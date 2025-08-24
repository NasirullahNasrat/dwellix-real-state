import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { listingsAPI } from "../services/api";
import ListingItem from "../components/ListingItem";
import Slider from "../components/Slider";
import Spinner from "../components/Spinner";
import "./Home.scss";

export default function Home() {
  const [offerListings, setOfferListings] = useState([]);
  const [rentListings, setRentListings] = useState([]);
  const [saleListings, setSaleListings] = useState([]);
  const [loading, setLoading] = useState({
    offers: true,
    rent: true,
    sale: true
  });
  const [error, setError] = useState(null);

  // Fetch all listings data
  useEffect(() => {
    async function fetchListings() {
      try {
        setError(null);
        
        // Fetch offers
        try {
          const offersResponse = await listingsAPI.getWithFilters({ 
            offer: true,
            limit: 3,
            sort: 'newest'
          });
          setOfferListings(offersResponse.data.data || offersResponse.data);
        } catch (err) {
          console.error("Error fetching offers:", err);
        } finally {
          setLoading(prev => ({ ...prev, offers: false }));
        }
        
        // Fetch rent listings
        try {
          const rentResponse = await listingsAPI.getWithFilters({ 
            type: "rent",
            limit: 3,
            sort: 'newest'
          });
          setRentListings(rentResponse.data.data || rentResponse.data);
        } catch (err) {
          console.error("Error fetching rent listings:", err);
        } finally {
          setLoading(prev => ({ ...prev, rent: false }));
        }
        
        // Fetch sale listings
        try {
          const saleResponse = await listingsAPI.getWithFilters({ 
            type: "sale",
            limit: 3,
            sort: 'newest'
          });
          setSaleListings(saleResponse.data.data || saleResponse.data);
        } catch (err) {
          console.error("Error fetching sale listings:", err);
        } finally {
          setLoading(prev => ({ ...prev, sale: false }));
        }
        
      } catch (error) {
        console.error("Error fetching listings:", error);
        setError("Failed to load listings. Please try again later.");
      }
    }
    
    fetchListings();
  }, []);

  const isLoading = loading.offers || loading.rent || loading.sale;

  if (isLoading) {
    return (
      <div className="home-page">
        <Slider />
        <div className="loading-container">
          <Spinner />
          <p className="loading-text">Finding the best properties for you...</p>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="home-page">
        <Slider />
        <div className="error-container">
          <div className="error-content">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h3>Something went wrong</h3>
            <p>{error}</p>
            <button onClick={() => window.location.reload()} className="retry-btn">
              Try Again
            </button>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="home-page">
      <Slider />
      
      <div className="home-content">
        {/* Welcome Message */}
        <div className="welcome-section">
          <h1>Find Your Dream Property</h1>
          <p>Discover the perfect place to call home with our curated selection of properties</p>
        </div>

        {/* Recent Offers Section */}
        <section className="listing-section">
          <div className="section-header">
            <h2 className="section-title">Recent Offers</h2>
            <Link to="/offers" className="view-all-link">
              View All Offers
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fillRule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clipRule="evenodd" />
              </svg>
            </Link>
          </div>
          
          {offerListings.length > 0 ? (
            <div className="listing-grid">
              {offerListings.map((listing) => (
                <ListingItem
                  key={listing.id}
                  listing={listing}
                  id={listing.id}
                />
              ))}
            </div>
          ) : (
            <div className="empty-state">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
              <p>No special offers available at the moment</p>
            </div>
          )}
        </section>

        {/* Places for Rent Section */}
        {/* <section className="listing-section">
          <div className="section-header">
            <h2 className="section-title">Places for Rent</h2>
            <Link to="/category/rent" className="view-all-link">
              View All Rentals
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fillRule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clipRule="evenodd" />
              </svg>
            </Link>
          </div>
          
          {rentListings.length > 0 ? (
            <div className="listing-grid">
              {rentListings.map((listing) => (
                <ListingItem
                  key={listing.id}
                  listing={listing}
                  id={listing.id}
                />
              ))}
            </div>
          ) : (
            <div className="empty-state">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
              <p>No rental properties available at the moment</p>
            </div>
          )}
        </section> */}

        {/* Places for Sale Section */}
        {/* <section className="listing-section">
          <div className="section-header">
            <h2 className="section-title">Places for Sale</h2>
            <Link to="/category/sale" className="view-all-link">
              View All Properties
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fillRule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clipRule="evenodd" />
              </svg>
            </Link>
          </div>
          
          {saleListings.length > 0 ? (
            <div className="listing-grid">
              {saleListings.map((listing) => (
                <ListingItem
                  key={listing.id}
                  listing={listing}
                  id={listing.id}
                />
              ))}
            </div>
          ) : (
            <div className="empty-state">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
              <p>No properties for sale available at the moment</p>
            </div>
          )}
        </section> */}
        
        {/* Call to Action Section */}
        <section className="cta-section">
          <div className="cta-content">
            <h2>Ready to find your perfect home?</h2>
            <p>Browse our complete collection of properties or list your own</p>
            <div className="cta-buttons">
              <Link to="/search" className="cta-btn primary">Browse Properties</Link>
              <Link to="/create-listing" className="cta-btn secondary">List a Property</Link>
            </div>
          </div>
        </section>
      </div>
    </div>
  );
}