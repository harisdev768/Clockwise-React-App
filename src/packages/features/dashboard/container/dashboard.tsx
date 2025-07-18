import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { SCREENS } from "../../screens";
import "./dashboard.css";
import apiClient from "../../authClient";
import Header from "../../../../components/header";

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
    <>
      <Header active="Dashboard" />
      <div className="dashboard-content">
        <div className="header-row">
          <h2>Dashboard</h2>
        </div>
        <div className="user-info">
          <p><strong>✅ User Logged in!</strong></p>
        </div>
      </div>
    </>
  );
}