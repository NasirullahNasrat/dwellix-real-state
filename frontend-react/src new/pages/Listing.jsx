import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import Spinner from "../components/Spinner";
import { Swiper, SwiperSlide } from "swiper/react";
import SwiperCore, { EffectFade, Autoplay, Navigation, Pagination } from "swiper";
import "swiper/css/bundle";
import "./Listing.scss";
import { FaBath, FaBed, FaChair, FaMapMarkerAlt, FaParking, FaShare } from "react-icons/fa";
import * as Scroll from "react-scroll";
import axios from "axios";
import Contact from "../components/Contact";
import { MapContainer, Popup, Marker, TileLayer } from "react-leaflet";
import { toast } from "react-toastify";

const Listing = () => {
  const [listing, setListing] = useState(null);
  const [loading, setLoading] = useState(true);
  const [shareLinkCopied, setShareLinkCopied] = useState(false);
  const [contactLandlord, setContactLandlord] = useState(false);
  const params = useParams();
  SwiperCore.use([Autoplay, Navigation, Pagination]);

  useEffect(() => {
    async function fetchListing() {
      try {
        const response = await axios.get(`/api/listings/${params.listingId}`);
        setListing(response.data.data);
      } catch (error) {
        toast.error("Could not fetch listing details");
      } finally {
        setLoading(false);
      }
    }
    fetchListing();
  }, [params.listingId]);

  if (loading) {
    return <Spinner />;
  }

  if (!listing) {
    return <div className="error">Listing not found</div>;
  }

  return (
    <main>
      <Swiper
        slidesPerView={1}
        navigation
        pagination={{ type: "progressbar" }}
        effect="fade"
        modules={[EffectFade]}
        autoplay={{ delay: 2000 }}
      >
        {listing.images.map((url, index) => (
          <SwiperSlide key={index}>
            <div
              className="swiper-image"
              style={{
                background: `url(${url}) center no-repeat`,
                backgroundSize: "cover",
                minHeight: "400px"
              }}
            ></div>
          </SwiperSlide>
        ))}
      </Swiper>

      <div
        className="copy-share-link"
        onClick={() => {
          navigator.clipboard.writeText(window.location.href);
          setShareLinkCopied(true);
          setTimeout(() => {
            setShareLinkCopied(false);
          }, 2000);
        }}
      >
        <FaShare className="copy-share-link__icon" />
      </div>

      {shareLinkCopied && <p className="copied-share-link">Link Copied</p>}

      <div className="house-info">
        <div className="house-info__details">
          <p className="house-info__details-title">
            {listing.name} - ₹{" "}
            {listing.offer
              ? listing.discounted_price.toString().replace(/\B(?=(?:(\d\d)+(\d)(?!\d))+(?!\d))/g, ",")
              : listing.regular_price.toString().replace(/\B(?=(?:(\d\d)+(\d)(?!\d))+(?!\d))/g, ",")}
            {listing.type === "rent" && " / month"}
          </p>
          <p className="house-info__details-address">
            <FaMapMarkerAlt className="house-info__details-address-icon" />
            {listing.address}
          </p>
          <div className="house-info__details-category-and-offer">
            <p className="house-info__details-category">
              For {listing.type === "rent" ? "Rent" : "Sale"}
            </p>
            {listing.offer && (
              <p className="house-info__details-offer">
                ₹
                {(listing.regular_price - listing.discounted_price)
                  .toString()
                  .replace(/\B(?=(?:(\d\d)+(\d)(?!\d))+(?!\d))/g, ",")}{" "}
                discount
              </p>
            )}
          </div>

          <p className="house-info__details-description">
            <span style={{ fontWeight: 600 }}>Description - </span>
            {listing.description}
          </p>
          <ul className="house-info__details-hbk">
            <li className="house-info__details-hbk-element">
              <FaBed className="house-info__details-hbk-element-icon" />
              {listing.bedrooms > 1 ? `${listing.bedrooms} Beds` : "1 Bed"}
            </li>
            <li className="house-info__details-hbk-element">
              <FaBath className="house-info__details-hbk-element-icon" />
              {listing.bathrooms > 1 ? `${listing.bathrooms} Baths` : "1 Bath"}
            </li>
            <li className="house-info__details-hbk-element">
              <FaParking className="house-info__details-hbk-element-icon" />
              {listing.parking ? "Parking spot" : "No parking"}
            </li>
            <li className="house-info__details-hbk-element">
              <FaChair className="house-info__details-hbk-element-icon" />
              {listing.furnished ? "Furnished" : "Not furnished"}
            </li>
          </ul>
          
          {listing.user && (
            <div className="house-info__details-contact-landlord-wrap">
              <button
                onClick={() => setContactLandlord(!contactLandlord)}
                className="house-info__details-contact-landlord-btn"
              >
                {contactLandlord ? "Hide Contact" : "Contact Landlord"}
              </button>
              {contactLandlord && <Contact landlord={listing.user} listing={listing} />}
            </div>
          )}
        </div>

        <div className="house-info__details-map">
          <MapContainer
            center={[listing.latitude, listing.longitude]}
            zoom={13}
            scrollWheelZoom={false}
            style={{ height: "100%", width: "100%" }}
          >
            <TileLayer
              attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
              url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
            />
            <Marker position={[listing.latitude, listing.longitude]}>
              <Popup>{listing.address}</Popup>
            </Marker>
          </MapContainer>
        </div>
      </div>
    </main>
  );
};

export default Listing;