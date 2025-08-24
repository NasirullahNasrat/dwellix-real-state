import React, { useState } from "react";
import "./SignIn.scss";
import { AiFillEyeInvisible, AiFillEye } from "react-icons/ai";
import { Link, useNavigate } from "react-router-dom";
import axios from "axios";
import { toast } from "react-toastify";
import Spinner from "../components/Spinner";

const SignIn = () => {
  const [showPassword, setShowPassword] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [formData, setFormData] = useState({
    email: "",
    password: "",
  });
  const { email, password } = formData;
  const navigate = useNavigate();

  function onChange(e) {
    setFormData(prev => ({
      ...prev,
      [e.target.id]: e.target.value,
    }));
  }

  async function onSubmit(e) {
    e.preventDefault();
    setIsLoading(true);
    
    try {
      await axios.post('localhost:8000/api/login', {
        email,
        password
      }, {
        withCredentials: true
      });
      
      navigate("/");
      toast.success("Logged in successfully");
    } catch (error) {
      const message = error.response?.data?.message || "Login failed";
      toast.error(message);
    } finally {
      setIsLoading(false);
    }
  }

  return (
    <section className="form-section">
      <h1 className="form-section__title">Sign In</h1>
      <div className="form-section__form-wrapper">
        <div className="form-section__image-wrap">
          <img
            src="https://wallpapers.com/images/hd/real-estate-house-keys-h79pvlxway8mwu2p.jpg"
            alt="key"
          />
        </div>
        <div className="form-section__form-wrap">
          <form onSubmit={onSubmit} className="form-section__form">
            <input
              type="email"
              id="email"
              placeholder="Email address"
              className="form-section__form-input"
              value={email}
              onChange={onChange}
              required
            />
            <div className="form-section__form-input-wrap">
              <input
                type={showPassword ? "text" : "password"}
                id="password"
                placeholder="Password"
                className="form-section__form-input-password"
                onChange={onChange}
                value={password}
                required
                minLength="6"
              />
              {showPassword ? (
                <AiFillEyeInvisible
                  className="form-section__form-input-password-eye"
                  onClick={() => setShowPassword(prev => !prev)}
                />
              ) : (
                <AiFillEye
                  className="form-section__form-input-password-eye"
                  onClick={() => setShowPassword(prev => !prev)}
                />
              )}
            </div>
            <div className="form-section__other-detail">
              <p className="form-section__account-signing">
                Don't have an account?{" "}
                <Link to="/sign-up">Register</Link>
              </p>
              <p className="form-section__forgot-password">
                <Link to="/forgot-password">Forgot password?</Link>
              </p>
            </div>
            <button 
              type="submit" 
              className="form-section__btn-signing"
              disabled={isLoading}
            >
              {isLoading ? <Spinner size="small" /> : "Sign In"}
            </button>
          </form>
          <div className="demo-credentials">
            <h3>Demo Credentials:</h3>
            <p><strong>Email:</strong> demo@example.com</p>
            <p><strong>Password:</strong> password</p>
          </div>
        </div>
      </div>
    </section>
  );
};

export default SignIn;