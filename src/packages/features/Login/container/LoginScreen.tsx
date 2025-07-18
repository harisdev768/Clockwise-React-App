// Imports
import styles from "../styles/LoginScreen.module.css";
import useLoginScreen from "../hooks/useLogin";
import { useEffect } from "react";

// Main Function
export default function Login() {
  const { setEmail, setPassword, handleLogin, handleNavigate } = useLoginScreen();

  const token = localStorage.getItem("accessToken");
  if (!token) {
  return (
    <div className={styles.container}>
      <div className={styles.loginBox}>
        <div className={styles.logo}>
          <img src="https://gillanesolutions.net/haris/wp-content/uploads/2025/07/logo-cw.png" alt="Clockwise Logo" />
        </div>

        <form onSubmit={handleLogin} className={styles.form}>
          <div className={styles.inputGroup}>
            <i className="fa-regular fa-envelope"> </i>
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
            <i className="fa-solid fa-lock"> </i>
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
          
          <a className={styles.forgetBtn} href="/forgot-password" >Forget Password?</a>

          
        </form>

        {/* <a href="#" className={styles.forgotLink}>Forgot Password?</a> */}
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
} else { 
  useEffect(() => {
    // Redirect to Dashboard if already logged in  
  handleNavigate();
  }, [handleNavigate]);
  return (
<>
<button className={styles.forgetBtn} onClick={handleNavigate}>
  </button>
</>  );
}
}
