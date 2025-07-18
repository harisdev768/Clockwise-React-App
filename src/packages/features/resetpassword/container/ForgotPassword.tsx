import React, { useState } from 'react';
import axios from 'axios';
import "../../resetpassword/styles/ForgotPassword.css"; // Reusing styles
import { useNavigate } from "react-router-dom";
import { SCREENS } from "../../screens";


const ForgotPassword = () => {

  const navigate = useNavigate();
  
  const handleOnReset = () => {
   navigate(SCREENS.LoginScreen);
  };


  const [email, setEmail] = useState('');
  const [status, setStatus] = useState<'idle' | 'success' | 'error'>('idle');
  const [message, setMessage] = useState('');

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const res = await axios.post("http://clockwise.local/forgot-password", {
        email
      },{
        withCredentials: true,
        headers: { "Content-Type": "application/json" }
      });

      setStatus('success');
      setMessage(res.data.message || "Reset link sent.");
      
      // ✅ Correct usage of setTimeout
      setTimeout(() => {
        handleOnReset();
      }, 3000); // Redirect after 3 seconds
          

    } catch (error: any) {
      setStatus('error');
      setMessage(error?.response?.data?.message || "Error sending reset link.");
    }
  };

  return (
    <div className="forgot-container">
      <div className="forgot-card">
        {/* <img
          src="https://gillanesolutions.net/haris/wp-content/uploads/2025/07/logo-cw.png"
          alt="Clockwise Logo"
          className="logo-head" ></img> */}
        <h2 className="forgot-title">Forgot Password</h2>

        {status !== 'idle' && (
          <div className={`forgot-msg ${status}`}>
            {message}
          </div>
        )}

        <form onSubmit={handleSubmit}>
          <label className="forgot-label">Email Address</label>
          <input
            type="email"
            className="forgot-input"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />

          <button type="submit" className="forgot-btn">Send Reset Link</button>
        </form>
      </div>
    </div>
  );
};

export default ForgotPassword;
