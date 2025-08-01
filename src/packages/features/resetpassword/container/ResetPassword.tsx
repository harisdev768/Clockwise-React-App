// ResetPassword.jsx
import React, { useState, useEffect } from 'react';
import { useSearchParams } from 'react-router-dom';
import axios from 'axios';
import '../../resetpassword/styles/ResetPassword.css'
import { useNavigate } from "react-router-dom";
import { SCREENS } from "../../screens";
import apiClient from '../../authClient';


const ResetPassword = () => {

  const navigate = useNavigate();

  const handleOnReset = () => {
    navigate(SCREENS.LoginScreen);
  };

  const [searchParams] = useSearchParams();
  const token = searchParams.get("token");

  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [status, setStatus] = useState<'idle' | 'success' | 'error'>('idle');
  const [message, setMessage] = useState('');

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    if (!token) {
      setStatus('error');
      setMessage("Invalid or missing token.");
      return;
    }

    if (password !== confirmPassword) {
      setStatus('error');
      setMessage("Passwords do not match.");
      return;
    }

    try {
      const response = await apiClient.post("/reset-password", {
        token,
        new_password: password,
      });

      setStatus('success');
      setMessage(response.data.message || "Password reset successful.");

      setTimeout(() => {
        handleOnReset();
      }, 3000);

    } catch (error: any) {
      setStatus('error');
      setMessage(error?.response?.data?.message || "Something went wrong.");
    }
  };

  return (
    <div className="reset-password-container">
      
      <div className="reset-password-card">
        
        {/* <img
          src="https://gillanesolutions.net/haris/wp-content/uploads/2025/07/logo-cw.png"
          alt="Clockwise Logo"
          className="reset-password-logo" ></img> */}
        <h2 className="reset-password-title">Reset Your Password</h2>

        {status !== 'idle' && (
          <div className={`status-message ${status === 'success' ? 'status-success' : 'status-error'}`}>
            {message}
          </div>
        )}

        <form onSubmit={handleSubmit}>
          <div className="form-group">
            <label className="form-label">New Password</label>
            <input
              type="password"
              className="form-input"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
              minLength={6}
            />
          </div>

          <div className="form-group">
            <label className="form-label">Confirm Password</label>
            <input
              type="password"
              className="form-input"
              value={confirmPassword}
              onChange={(e) => setConfirmPassword(e.target.value)}
              required
            />
          </div>

          <button
            type="submit"
            className="submit-button"
          >
            Reset Password
          </button>
        </form>
      </div>
    </div>
  );
};

export default ResetPassword;