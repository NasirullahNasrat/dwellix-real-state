import React, { useState } from "react";
import "./SignIn.scss"; // using same SCSS for consistency
import { Link, useNavigate } from "react-router-dom";
import { AiFillEyeInvisible, AiFillEye } from "react-icons/ai";
import { authAPI } from "../services/api";
import { toast } from "react-toastify";

const SignUp = () => {
  const [showPassword, setShowPassword] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
  });

  const { name, email, password, password_confirmation } = formData;
  const navigate = useNavigate();

  const emailIsValid = /\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/.test(email);
  const passwordIsValid = password.length >= 6 && password.trim() !== "";
  const nameIsValid = name.length >= 3 && name.trim() !== "";
  const passwordsMatch = password === password_confirmation;

  function onChange(e) {
    setFormData((prev) => ({
      ...prev,
      [e.target.id]: e.target.value,
    }));
  }

  async function onSubmit(e) {
    e.preventDefault();

    if (!nameIsValid) return toast.error("Name should be at least 3 characters");
    if (!emailIsValid) return toast.error("Enter a valid email");
    if (!passwordIsValid) return toast.error("Password should be at least 6 characters");
    if (!passwordsMatch) return toast.error("Passwords do not match");

    setIsLoading(true);
    try {
      const response = await authAPI.register(formData);

      localStorage.setItem("authToken", response.data.token);
      localStorage.setItem("user", JSON.stringify(response.data.user));

      toast.success("Sign up successful 🎉");
      navigate("/");
    } catch (error) {
      const errorMessage =
        error.response?.data?.message || error.message || "Sign up failed";
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
          <h1 className="signin-title">Create an Account ✨</h1>
          <p className="signin-subtitle">Sign up to get started</p>

          <form onSubmit={onSubmit} className="signin-form">
            <input
              type="text"
              id="name"
              value={name}
              onChange={onChange}
              placeholder="Full name"
              className="signin-input"
              required
            />

            <input
              type="email"
              id="email"
              value={email}
              onChange={onChange}
              placeholder="Email address"
              className="signin-input"
              required
            />

            <div className="signin-input-wrap">
              <input
                type={showPassword ? "text" : "password"}
                id="password"
                value={password}
                onChange={onChange}
                placeholder="Password"
                className="signin-input"
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

            <div className="signin-input-wrap">
              <input
                type={showPassword ? "text" : "password"}
                id="password_confirmation"
                value={password_confirmation}
                onChange={onChange}
                placeholder="Confirm Password"
                className="signin-input"
                required
              />
            </div>

            <button
              className="signin-btn"
              type="submit"
              disabled={isLoading}
            >
              {isLoading ? "Signing Up..." : "Sign Up"}
            </button>
          </form>

          <p className="signin-footer">
            Already have an account? <Link to="/sign-in">Sign in</Link>
          </p>
        </div>
      </div>
    </section>
  );
};

export default SignUp;
