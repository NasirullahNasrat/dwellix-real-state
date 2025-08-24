// components/Header.jsx
import React, { useEffect, useState } from "react";
import "./Header.scss";
import { Link, NavLink } from "react-router-dom";
import axios from "axios";

const Header = () => {
  const [pageState, setPageState] = useState({
    path: "sign-in",
    menuName: "Sign in",
  });
  const { path, menuName } = pageState;

  useEffect(() => {
    async function checkAuth() {
      try {
        const response = await axios.get('localhost:8000/api/user', {
          withCredentials: true
        });
        if (response.data) {
          setPageState({
            path: "profile",
            menuName: "Profile",
          });
        }
      } catch (error) {
        setPageState({
          path: "sign-in",
          menuName: "Sign in",
        });
      }
    }
    
    checkAuth();
  }, []);

  return (
    <div className="header">
      <header className="header__container">
        <Link to="/" className="header__logo">
          <img
            src="https://static.rdc.moveaws.com/images/logos/rdc-logo-default.svg"
            alt="logo"
          ></img>
        </Link>
        <nav>
          <ul className="header__menus">
            <NavLink to="/" className="header__menu">
              Home
            </NavLink>
            <NavLink to="/offers" className="header__menu">
              Offers
            </NavLink>
            <NavLink to={`/${path}`} className="header__menu">
              {menuName}
            </NavLink>
          </ul>
        </nav>
      </header>
    </div>
  );
};

export default Header;