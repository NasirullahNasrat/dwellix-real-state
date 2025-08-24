// components/Contact.jsx
import { useState } from "react";
import { toast } from "react-toastify";
import "./Contact.scss";

export default function Contact({ landlord, listing }) {
  const [message, setMessage] = useState("");

  function onChange(e) {
    setMessage(e.target.value);
  }

  return (
    <>
      {landlord && (
        <div className="contact">
          <p>
            Contact <span style={{ fontWeight: "600" }}>{landlord.name}</span>{" "}
            for the{" "}
            <span style={{ fontWeight: "600" }}>
              {` ${listing.name.toLowerCase()}`}
            </span>
          </p>
          <div className="contact__textarea-wrap">
            <textarea
              name="message"
              id="message"
              rows="2"
              value={message}
              onChange={onChange}
              className="contact__textarea"
            ></textarea>
          </div>
          <a
            href={`mailto:${landlord.email}?Subject=${listing.name}&body=${message}`}
          >
            <button className="contact__send-btn" type="button">
              Send Message
            </button>
          </a>
        </div>
      )}
    </>
  );
}