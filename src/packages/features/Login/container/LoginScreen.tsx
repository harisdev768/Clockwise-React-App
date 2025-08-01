import styles from "../styles/LoginScreen.module.css";
import useLoginScreen from "../hooks/useLogin";
import { useEffect, useState } from "react";
import apiClient from "../../authClient";

export default function Login() {
  const { setEmail, setPassword, handleLogin, handleNavigate } = useLoginScreen();
  const [checkingAuth, setCheckingAuth] = useState(true);

  useEffect(() => {
    // Make a call to /me to check if JWT cookie is valid
    apiClient.get("/me", { withCredentials: true })
      .then((res) => {
        if (res.data?.success) {
          handleNavigate(); // Redirect to dashboard
        } else {
          setCheckingAuth(false); // Show login screen
        }
      })
      .catch(() => {
        setCheckingAuth(false); // Cookie invalid or expired
      });
  }, [handleNavigate]);

  if (checkingAuth) return null; // Optional: show loader here

  return (
    <div className={styles.container}>
      <div className={styles.loginBox}>
        <div className={styles.logo}>
          <img src="/logo.png" alt="Clockwise Logo" />
        </div>

        <form onSubmit={handleLogin} className={styles.form}>
          <div className={styles.inputGroup}>
            <i className="fa-regular fa-envelope"></i>
            <input
              type="text"
              name="email"
              placeholder="Email/Username"
              onChange={(e) => setEmail(e.target.value)}
              autoComplete="username"
              required
            />
          </div>

          <div className={styles.inputGroup}>
            <i className="fa-solid fa-lock"></i>
            <input
              type="password"
              name="password"
              placeholder="Password"
              onChange={(e) => setPassword(e.target.value)}
              autoComplete="current-password"
              required
            />
          </div>

          <input type="submit" value="Log in" className={styles.loginBtn} />

          <a className={styles.forgetBtn} href="/forgot-password">Forget Password?</a>
        </form>
      </div>

      <footer className={styles.footer}>
        <a href="#">TCP Software</a> | 
        <a href="#">Legal</a> | 
        <a href="#">Privacy Policy</a> | 
        <a href="#">Support & Tutorials</a> | 
        <a href="#">Request Account</a>
        <div>©2025 Humanity.com Inc.</div>
      </footer>
    </div>
  );
}
