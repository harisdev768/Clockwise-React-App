import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { SCREENS } from "../../screens";
import "./dashboard.css";
import apiClient from "../../authClient";
import Header from "../../../../components/header";

interface UserData {
  name: string;
  email: string;
  role: string;
  user_id: number;
  status: number;
  iat: number;
  exp: number;
}

export default function Dashboard() {
  const navigate = useNavigate();
  const [isVerified, setIsVerified] = useState<boolean | null>(null);
  const [user, setUser] = useState<UserData | null>(null);

  useEffect(() => {
    const checkAuth = async () => {
      try {
        const res = await apiClient.get("/me");
        if (res.data?.success === true) {
          const userData: UserData = res.data.data;
          setUser(userData);
          setIsVerified(true);

          if (userData.role === "Manager") {
            navigate("/staff"); // or SCREENS.StaffScreen
          }
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

  if (isVerified === null) return <p>Loading...</p>;
  if (isVerified === false || !user) return null;

  return (
    <>
      <Header active="Dashboard" />
      <div className="dashboard-content">
        <div className="container-fluid">
          <div className="header-row">
            <h2>Welcome, {user.name}!</h2>
          </div>
          <div className="user-card">
            <div className="user-card-left">
              <i className="fas fa-user-circle user-icon"></i>
            </div>
            <div className="user-card-right">
              <h2>User Information</h2>
              <ul className="user-details">
                <li><h5><strong>Name:</strong> {user.name}</h5></li>
                <li><h5><strong>Email:</strong> {user.email}</h5></li>
                <li><h5><strong>Role:</strong> {user.role}</h5></li>
                <li><h5><strong>Status:</strong> {user.status === 1 ? "Active" : "Inactive"}</h5></li>
                <li><h5><strong>User ID:</strong> {user.user_id}</h5></li>
              </ul>
            </div>
          </div>
        </div>

      </div>
    </>
  );
}
