import { Link, useNavigate } from "react-router-dom";
import { FcHome } from "react-icons/fc";
import "./Profile.scss";
import { useEffect, useState } from "react";
import { toast } from "react-toastify";
import Notiflix from "notiflix";
import axios from "axios";
import ListingItem from "../components/ListingItem";
import Spinner from "../components/Spinner";

export default function Profile() {
  const navigate = useNavigate();
  const [changeDetail, setChangeDetail] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [listIsLoading, setListIsLoading] = useState(true);
  const [listings, setListings] = useState(null);
  const [formData, setFormData] = useState({
    name: "",
    email: ""
  });

  useEffect(() => {
    async function fetchUserData() {
      try {
        const response = await axios.get('localhost:8000/api/user', {
          withCredentials: true
        });
        setFormData({
          name: response.data.name,
          email: response.data.email
        });
        fetchUserListings();
      } catch (error) {
        navigate("/sign-in");
      }
    }

    async function fetchUserListings() {
      try {
        const response = await axios.get('localhost:8000/api/listings/user', {
          withCredentials: true
        });
        setListings(response.data.data);
      } catch (error) {
        toast.error("Could not fetch your listings");
      } finally {
        setListIsLoading(false);
      }
    }

    fetchUserData();
  }, [navigate]);

  function onLogout() {
    axios.post('localhost:8000/api/logout', {}, {
      withCredentials: true
    }).then(() => {
      navigate("/");
    }).catch(error => {
      toast.error("Could not log out");
    });
  }

  function onChange(e) {
    setFormData(prev => ({
      ...prev,
      [e.target.id]: e.target.value
    }));
  }

  async function onSubmit() {
    setIsLoading(true);
    try {
      await axios.put('localhost:8000/api/user/profile', formData, {
        withCredentials: true
      });
      toast.success("Profile updated successfully");
    } catch (error) {
      toast.error(error.response?.data?.message || "Update failed");
    } finally {
      setIsLoading(false);
    }
  }

  const confirmDelete = (listingId) => {
    Notiflix.Confirm.show(
      "Delete Listing",
      "Are you sure you want to delete this listing?",
      "Delete",
      "Cancel",
      async () => {
        try {
          await axios.delete(`localhost:8000/api/listings/${listingId}`, {
            withCredentials: true
          });
          setListings(prev => prev.filter(listing => listing.id !== listingId));
          toast.success("Listing deleted");
        } catch (error) {
          toast.error("Failed to delete listing");
        }
      },
      () => {},
      {
        width: "320px",
        borderRadius: "3px",
        titleColor: "red",
        okButtonBackground: "red",
        cssAnimationStyle: "zoom",
      }
    );
  };

  function onEdit(listingId) {
    navigate(`/edit-listing/${listingId}`);
  }

  return (
    <>
      <section className="profile">
        <h1 className="profile__header">My Profile</h1>
        <div className="profile__form-btn-wrap">
          <form className="profile__form">
            <input
              type="text"
              id="name"
              value={formData.name}
              disabled={!changeDetail}
              onChange={onChange}
              className={`profile__form-input ${
                changeDetail ? "profile__form-input--modifier" : ""
              }`}
            />
            <input
              type="email"
              id="email"
              disabled
              className="profile__form-input"
              value={formData.email}
            />
            <div className="profile__form-links">
              <p className="profile__form-name-change-link">
                Do you want to change your name?
                <span
                  onClick={() => {
                    changeDetail && onSubmit();
                    setChangeDetail(prev => !prev);
                  }}
                  className="profile__form-name-change-link-name"
                >
                  {!isLoading && changeDetail ? (
                    "Apply change"
                  ) : isLoading ? (
                    <div className="loader--little"></div>
                  ) : (
                    "Edit"
                  )}
                </span>
              </p>
              <p onClick={onLogout} className="profile__form-sign-out">
                Sign out
              </p>
            </div>
          </form>
          <button type="submit" className="profile__home-sell-btn">
            <Link to="/create-listing" className="profile__home-sell-btn-link">
              <FcHome className="profile__home-sell-btn-logo" />
              Sell or rent your home
            </Link>
          </button>
        </div>
      </section>
      <div className="user-listings-section">
        {listIsLoading ? (
          <Spinner />
        ) : listings && listings.length > 0 ? (
          <>
            <h2 className="user-listings-section__header">My Listings</h2>
            <ul className="user-listings-section__listing">
              {listings.map((listing) => (
                <ListingItem
                  key={listing.id}
                  id={listing.id}
                  listing={listing}
                  onDelete={() => confirmDelete(listing.id)}
                  onEdit={() => onEdit(listing.id)}
                />
              ))}
            </ul>
          </>
        ) : (
          <p className="user-listings-section__no-listings">
            You don't have any listings yet
          </p>
        )}
      </div>
    </>
  );
}