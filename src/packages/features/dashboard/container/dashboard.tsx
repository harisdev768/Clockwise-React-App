import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { SCREENS } from "../../screens";
import "./dashboard.css";
import apiClient from "../../authClient";

export default function Dashboard() {
  const navigate = useNavigate();
  const [isVerified, setIsVerified] = useState<boolean | null>(null);

  useEffect(() => {
    const checkAuth = async () => {
      try {
        const res = await apiClient.get("/me");
        if (res.data?.success === true) {
          setIsVerified(true);
        } else {
          throw new Error("Unauthorized");
        }
      } catch (error) {
        console.error("Auth check failed", error);
        localStorage.removeItem("accessToken");
        localStorage.removeItem("accessTokenExpiry");
        navigate(SCREENS.LoginScreen);
      }
    };

    checkAuth();
  }, [navigate]);

  if (isVerified === null) return <p>Loading...</p>; // While checking auth
  if (isVerified === false) return null; // Optional: can show unauthorized message

  return (
    <div className="dashboard-wrapper bg-light">
      {/* Topbar */}
      <div className="dashboard-topbar">
        <img
          src="https://gillanesolutions.net/haris/wp-content/uploads/2025/07/logo-cw.png"
          className="logo"
          alt="Clockwise Logo"
        />
        <nav className="dashboard-nav">
          <button><i className="fa-solid fa-gauge-high"></i> Dashboard</button>
          <button><i className="fa-solid fa-calendar-days"></i> ShiftPlanning</button>
          <button><i className="fa-solid fa-clock"></i> Time Clock</button>
          <button><i className="fa-solid fa-plane-departure"></i> Leave</button>
          <button><i className="fa-solid fa-graduation-cap"></i> Training</button>
          <button><i className="fa-solid fa-users"></i> Staff</button>
          <button><i className="fa-solid fa-chart-line"></i> Reports</button>
          <button><i className="fa-solid fa-people-group"></i> Group Accounts</button>
        </nav>
      </div>

      {/* Main Content */}
      <div className="dashboard-content">
        <div className="header-row">
          <h2>Dashboard</h2>
          <div>
            <button
              className="widget-btn"
              style={{ marginLeft: "10px", backgroundColor: "red", color: "white" }}
              onClick={() => {
                apiClient.post("/logout").then(() => {
                  localStorage.removeItem("accessToken");
                  localStorage.removeItem("accessTokenExpiry");
                  window.location.href = "/";
                });
              }}
            >
              Logout
            </button>
          </div>
        </div>

        <div className="user-info">
          <p><strong>✅ User Logged in!</strong></p>
        </div>
      </div>
    </div>
  );
}