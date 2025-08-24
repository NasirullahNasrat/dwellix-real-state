import React, { useState } from "react";
import "./SignIn.scss";
import { AiFillEyeInvisible, AiFillEye } from "react-icons/ai";
import { Link, useNavigate } from "react-router-dom";
import { authAPI } from "../services/api";
import { toast } from "react-toastify";

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
    setFormData((prev) => ({
      ...prev,
      [e.target.id]: e.target.value,
    }));
  }

  async function onSubmit(e) {
    e.preventDefault();
    setIsLoading(true);
    try {
      const response = await authAPI.login(formData);

      localStorage.setItem("authToken", response.data.token);
      localStorage.setItem("user", JSON.stringify(response.data.user));

      navigate("/");
      toast.success("Login successful 🎉");
    } catch (error) {
      const errorMessage =
        error.response?.data?.message || error.message || "Login failed";
      toast.error(errorMessage);
    } finally {
      setIsLoading(false);
    }
  }

  return (
    <section className="signin-section">
      <div className="signin-card">
        <div className="signin-image">
          <img
            src="https://wallpapers.com/images/hd/real-estate-house-keys-h79pvlxway8mwu2p.jpg"
            alt="Real estate key"
          />
        </div>

        <div className="signin-form-wrap">
          <h1 className="signin-title">Welcome Back 👋</h1>
          <p className="signin-subtitle">Sign in to continue</p>

          <form onSubmit={onSubmit} className="signin-form">
            <input
              type="email"
              id="email"
              placeholder="Email address"
              className="signin-input"
              value={email}
              onChange={onChange}
              required
            />

            <div className="signin-input-wrap">
              <input
                type={showPassword ? "text" : "password"}
                id="password"
                placeholder="Password"
                className="signin-input"
                value={password}
                onChange={onChange}
                required
              />
              {showPassword ? (
                <AiFillEyeInvisible
                  className="signin-eye"
                  onClick={() => setShowPassword((prev) => !prev)}
                />
              ) : (
                <AiFillEye
                  className="signin-eye"
                  onClick={() => setShowPassword((prev) => !prev)}
                />
              )}
            </div>

            <button
              className="signin-btn"
              type="submit"
              disabled={isLoading}
            >
              {isLoading ? "Signing In..." : "Sign In"}
            </button>
          </form>

          <p className="signin-footer">
            Don’t have an account? <Link to="/sign-up">Register</Link>
          </p>

        </div>
      </div>
    </section>
  );
};

export default SignIn;
