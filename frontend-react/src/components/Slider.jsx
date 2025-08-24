import { Swiper, SwiperSlide } from "swiper/react";
import SwiperCore, {
  EffectFade,
  Autoplay,
  Navigation,
  Pagination,
} from "swiper";
import "swiper/css/bundle";
import { useNavigate } from "react-router-dom";
import "./Slider.scss";
import { useState } from "react";

export default function Slider() {
  SwiperCore.use([Autoplay, Navigation, Pagination]);
  const navigate = useNavigate();
  const [activeIndex, setActiveIndex] = useState(0);

  // Modern, high-quality home images
  const slides = [
    {
      image: "https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&auto=format&fit=crop&q=80&ixlib=rb-4.0.3",
      title: "Luxury Modern Villa",
      address: "123 Palm Beach Drive, Miami",
      price: "$ 85,000",
      period: "/ month",
      features: ["4 Beds", "3 Baths", "3200 sqft"],
      offer: "Special Offer"
    },
    {
      image: "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1200&auto=format&fit=crop&q=80&ixlib=rb-4.0.3",
      title: "Contemporary Downtown Loft",
      address: "456 Skyline Avenue, New York",
      price: "$ 1,20,000",
      period: "/ month",
      features: ["3 Beds", "2 Baths", "1800 sqft"],
      offer: "New Listing"
    },
    {
      image: "https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?w=1200&auto=format&fit=crop&q=80&ixlib=rb-4.0.3",
      title: "Minimalist Family Home",
      address: "789 Oak Street, San Francisco",
      price: "$ 95,000",
      period: "/ month",
      features: ["5 Beds", "4 Baths", "2800 sqft"],
      offer: "Featured"
    }
  ];

  return (
    <div className="slider-container">
      <Swiper
        slidesPerView={1}
        navigation={{
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        }}
        pagination={{ 
          type: "fraction",
          el: '.swiper-pagination-custom'
        }}
        effect="fade"
        modules={[EffectFade]}
        autoplay={{ delay: 5000, disableOnInteraction: false }}
        speed={1000}
        onSlideChange={(swiper) => setActiveIndex(swiper.activeIndex)}
        className="main-swiper"
      >
        {slides.map((slide, index) => (
          <SwiperSlide key={index}>
            <div 
              className="slide-background"
              style={{ backgroundImage: `url(${slide.image})` }}
            />
            <div className="slide-overlay" />
            <div className="slide-content">
              <div className="slide-text">
                <h1 className="slide-title">{slide.title}</h1>
                <p className="slide-address">{slide.address}</p>
                <div className="slide-price">
                  <span className="price-amount">{slide.price}</span>
                  <span className="price-period">{slide.period}</span>
                </div>
                {slide.offer && (
                  <span className="offer-badge">{slide.offer}</span>
                )}
              </div>
              <div className="slide-features">
                {slide.features.map((feature, i) => (
                  <div key={i} className="feature">
                    <span>{feature}</span>
                  </div>
                ))}
              </div>
            </div>
          </SwiperSlide>
        ))}
      </Swiper>

      {/* Custom Navigation */}
      <div className="swiper-button-prev">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
        </svg>
      </div>
      <div className="swiper-button-next">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
        </svg>
      </div>

      {/* Custom Pagination */}
      <div className="swiper-pagination-custom">
        <span className="current-index">0{activeIndex + 1}</span>
        <span className="divider">/</span>
        <span className="total-slides">0{slides.length}</span>
      </div>

      {/* Explore Button */}
      <div className="explore-button">
        <button onClick={() => navigate('/homes')}>
          Explore Properties
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
          </svg>
        </button>
      </div>
    </div>
  );
}