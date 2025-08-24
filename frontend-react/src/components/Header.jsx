import React, { useEffect, useState } from "react";
import "./Header.scss";
import { Link, NavLink } from "react-router-dom";
import { authAPI } from "../services/api"; 
import { Menu, X } from "lucide-react"; // modern icons
import logo from "./logo.png"; // Importing the logo

const Header = () => {
  const [pageState, setPageState] = useState({
    path: "sign-in",
    menuName: "Sign in",
  });
  const [isLoading, setIsLoading] = useState(true);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const { path, menuName } = pageState;

  useEffect(() => {
    const checkAuthStatus = async () => {
      try {
        const token = localStorage.getItem("authToken");

        if (token) {
          await authAPI.profile();
          setPageState({ path: "profile", menuName: "Profile" });
        } else {
          setPageState({ path: "sign-in", menuName: "Sign in" });
        }
      } catch {
        localStorage.removeItem("authToken");
        localStorage.removeItem("user");
        setPageState({ path: "sign-in", menuName: "Sign in" });
      } finally {
        setIsLoading(false);
      }
    };

    checkAuthStatus();
    window.addEventListener("storage", checkAuthStatus);
    return () => window.removeEventListener("storage", checkAuthStatus);
  }, []);

  const menuItems = (
    <>
      <NavLink to="/" className="header__menu">Home</NavLink>
      <NavLink to="/offers" className="header__menu">Offers</NavLink>
      <NavLink to={`/${path}`} className="header__menu">
        {isLoading ? "Loading..." : menuName}
      </NavLink>
    </>
  );

  return (
    <div className="header">
      <header className="header__container">
        <Link to="/" className="header__logo">
          <img
            src={logo} // Using the imported logo
            alt="logo"
          />
        </Link>

        {/* Desktop menu */}
        <nav className="header__nav">{menuItems}</nav>

        {/* Mobile toggle */}
        <button
          className="header__mobile-toggle"
          onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
        >
          {isMobileMenuOpen ? <X size={24} /> : <Menu size={24} />}
        </button>
      </header>

      {/* Mobile menu dropdown */}
      {isMobileMenuOpen && (
        <div className="header__mobile-menu">{menuItems}</div>
      )}
    </div>
  );
};

export default Header;