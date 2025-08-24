import Moment from "react-moment";
import { Link } from "react-router-dom";
import { MdLocationOn } from "react-icons/md";
import { FaTrash } from "react-icons/fa";
import { MdEdit } from "react-icons/md";
import "./ListingItem.scss";

export default function ListingItem({ listing, id, onEdit, onDelete }) {
  // Get the first image URL
  const imageUrl = listing.images?.[0]?.image_url 
    ? (listing.images[0].image_url.startsWith('http') 
        ? listing.images[0].image_url 
        : `http://localhost:8000${listing.images[0].image_url}`)
    : '/placeholder-image.jpg';

  return (
    <li className="listingItem">
      <Link
        className="listingItem__link"
        to={`/category/${listing.type}/${id}`}
      >
        <img
          className="listingItem__image"
          loading="lazy"
          src={imageUrl}
          alt="house"
        />
        <Moment className="listingItem__moment" fromNow>
          {new Date(listing.created_at)}
        </Moment>
        <div className="listingItem__details">
          <div className="listingItem__wrap">
            <MdLocationOn className="listingItem__location-icon" />
            <p className="listingItem__location-address">{listing.address}</p>
          </div>
          <p className="listingItem__name">{listing.name}</p>
          <p className="listingItem__price">
            $
            {listing.offer
              ? parseFloat(listing.discountedPrice).toLocaleString('en-IN')
              : parseFloat(listing.regularPrice).toLocaleString('en-IN')}
            {listing.type === "rent" && " / month"}
          </p>
          <div className="listingItem__wrap listingItem__wrap--increase-margin">
            <div className="listingItem__wrap">
              <p className="listingItem__bath-and-bed">
                {listing.bedrooms > 1 ? `${listing.bedrooms} Beds` : "1 Bed"}
              </p>
            </div>
            <div className="listingItem__wrap">
              <p className=" listingItem__bath-and-bed">
                {listing.bathrooms > 1
                  ? `${listing.bathrooms} Baths`
                  : "1 Bath"}
              </p>
            </div>
          </div>
        </div>
      </Link>
      {onDelete && (
        <FaTrash
          className="listingItem__manipulate-icon"
          onClick={() => onDelete(listing.id)}
        />
      )}
      {onEdit && (
        <MdEdit
          className="listingItem__manipulate-icon"
          style={{ right: "1.75rem", color: "darkBlue" }}
          onClick={() => onEdit(listing.id)}
        />
      )}
    </li>
  );
}