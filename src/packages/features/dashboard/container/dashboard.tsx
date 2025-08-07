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

  // Status states
  const [clockStatus, setClockStatus] = useState<boolean>(false);
  const [breakStatus, setBreakStatus] = useState<boolean>(false);
  const [clockId, setClockId] = useState<number | null>(null);

  const [loadingClock, setLoadingClock] = useState(false);

  useEffect(() => {
    const checkAuth = async () => {
      try {
        const res = await apiClient.get("/me");
        if (res.data?.success === true) {
          const userData: UserData = res.data.data;
          setUser(userData);
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

  useEffect(() => {
    const fetchClockStatus = async () => {
      if (!user) return;
      try {
        const res = await apiClient.get(`/clock/status?user_id=${user.user_id}`);
        if (res.data?.success === true) {
          setClockStatus(res.data.data.clockStatus);
          setBreakStatus(res.data.data.breakStatus);
          setClockId(res.data.data.clock_id);
        }
      } catch (err) {
        console.error("Failed to fetch clock status", err);
      }
    };
    fetchClockStatus();
  }, [user]);

  const showToast = (msg: string) => {
    const toastBody = document.getElementById("toastBody");
    if (toastBody) toastBody.innerHTML = msg;

    const toastEl = document.getElementById("clockToast");
    if (toastEl) {
      const toast = new (window as any).bootstrap.Toast(toastEl);
      toast.show();
    }
  };

  const handleClock = async () => {
    if (!user) return;
    try {
      setLoadingClock(true);
      const action = clockStatus ? "out" : "in";

      const res = await apiClient.post("/clock", {
        user_id: user.user_id,
        action,
      });

      if (res.data?.success === true) {
        setClockStatus(action === "in");
        setBreakStatus(false);
        setClockId(res.data.data.clock_id ?? null); // update clock id after clock-in
        showToast(res.data.message);
      }
    } catch (err) {
      console.error(err);
      showToast("Something went wrong!");
    } finally {
      setLoadingClock(false);
    }
  };

  const handleBreak = async (breakAction: "start" | "end") => {
    if (!user || !clockId) return;
    try {
      setLoadingClock(true);
      const res = await apiClient.post("/break", {
        user_id: user.user_id,
        clock_id: clockId,
        action: breakAction,
      });

      if (res.data?.success === true) {
        setBreakStatus(breakAction === "start");
        showToast(res.data.message);
      }
    } catch (err) {
      console.error(err);
      showToast("Something went wrong!");
    } finally {
      setLoadingClock(false);
    }
  };

  const getDuration = (start: Date, end: Date) => {
    const ms = end.getTime() - start.getTime();
    const totalMinutes = Math.floor(ms / 60000);
    const hours = Math.floor(totalMinutes / 60);
    const minutes = totalMinutes % 60;
    return `${String(hours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}`;
  };

  const [totalShift, setTotalShift] = useState("00:00");
  useEffect(() => {
    const fetchClockStatus = async () => {
      if (!user) return;
      try {
        const res = await apiClient.get(`/clock/status?user_id=${user.user_id}`);
        if (res.data?.success === true) {
          const data = res.data.data;
          setClockStatus(data.clockStatus);
          setBreakStatus(data.breakStatus);
          setClockId(data.clock_id);

          // CALCULATE TIME
          if (data.clockStatus) {
            const start = new Date(data.clock_in_at);
            const now = new Date();
            setTotalShift(getDuration(start, now));
          } else if (data.clock_out_at) {
            const start = new Date(data.clock_in_at);
            const end = new Date(data.clock_out_at);
            setTotalShift(getDuration(start, end));
          } else {
            setTotalShift("00:00");
          }
        }
      } catch (err) {
        console.error("Failed to fetch clock status", err);
      }
    };
    fetchClockStatus();
  }, [user]);


  if (isVerified === null) return <p>Loading...</p>;
  if (isVerified === false || !user) return null;

  return (
    <>
      <Header active="Dashboard" />

      {/* Toast Container */}
      <div className="toast-wrapper" style={{ zIndex: 9999 }}>
        <div
          id="clockToast"
          className="toast"
          role="alert"
          aria-live="assertive"
          aria-atomic="true"
        >
          <div className="row align-items-center">
            <div className="col-10">
              <div className="toast-body" id="toastBody">
                Success
              </div>
            </div>
            <div className="col-2 text-end">
              <button
                type="button"
                className="btn-close"
                data-bs-dismiss="toast"
                aria-label="Close"
              ></button>
            </div>
          </div>
        </div>
      </div>

      <div className="dashboard-content">
        <div className="container-fluid">
          <div className="header-row">
            <h3>Welcome, {user.name}!</h3>
          </div>
          <div className="row">
            <div className="card mt-2 col-md-4">
              <div className="card-body">
                <h5 className="card-title">Time Clock</h5>
                <p className="card-subtitle mb-2 text-muted">
                  {new Date().toLocaleDateString("en-US", {
                    month: "short",
                    day: "numeric",
                    year: "numeric",
                  })}{" "}
                  -{" "}
                  {new Date().toLocaleTimeString("en-US", {
                    hour: "2-digit",
                    minute: "2-digit",
                  })}
                </p>
                <p className="mb-4">
                  <strong>Total Hours:</strong> {totalShift} hours.
                </p>


                <ul className="timeline list-unstyled">
                  <li className="d-flex mb-3">
                    <span className="me-2 mt-1">
                      <span
                        className={`badge rounded-circle ${clockStatus ? "bg-success" : "bg-danger"
                          }`}
                        style={{ width: "10px", height: "10px" }}
                      ></span>
                    </span>
                    <div className="flex-grow-1 bg-light p-2 rounded">
                      {clockStatus ? "Clocked in." : "Not clocked in."}
                    </div>
                  </li>
                  {clockStatus && (
                    <li className="d-flex mb-3">
                      <span className="me-2 mt-1">
                        <span
                          className={`badge rounded-circle ${breakStatus ? "bg-warning" : "bg-secondary"
                            }`}
                          style={{ width: "10px", height: "10px" }}
                        ></span>
                      </span>
                      <div className="flex-grow-1 bg-light p-2 rounded">
                        {breakStatus ? "On Break." : "Working."}
                      </div>
                    </li>
                  )}
                </ul>

                {!clockStatus && (
                  <button
                    onClick={handleClock}
                    className="btn btn-success"
                    disabled={loadingClock}
                  >
                    {loadingClock ? "Please wait..." : "Clock In"}
                  </button>
                )}

                {clockStatus && !breakStatus && (
                  <>
                    <button
                      className="btn btn-secondary ms-3"
                      disabled={loadingClock}
                      onClick={() => handleBreak("start")}
                    >
                      Start Break
                    </button>
                    <button
                      onClick={handleClock}
                      className="btn btn-danger"
                      disabled={loadingClock}
                    >
                      {loadingClock ? "Please wait..." : "Clock Out"}
                    </button>
                  </>
                )}

                {clockStatus && breakStatus && (
                  <button
                    className="btn btn-warning"
                    disabled={loadingClock}
                    onClick={() => handleBreak("end")}
                  >
                    {loadingClock ? "Please wait..." : "End Break"}
                  </button>
                )}
              </div>
            </div>

            {/* USER INFO */}
            <div className="col-md-8">
              <div className="card mt-2">
                <div className="card-body">
                  <h5 className="card-title">User Information</h5>
                  <div className="row">
                    <div className="col-md-2">
                      <div className="user-card-left">
                        <i className="fas fa-user-circle user-icon"></i>
                      </div>
                    </div>
                    <div className="col-md-10">
                      <ul className="list-group list-group-flush info-list">
                        <li className="list-group-item">
                          <strong>Name:</strong> {user.name}
                        </li>
                        <li className="list-group-item">
                          <strong>Email:</strong> {user.email}
                        </li>
                        <li className="list-group-item">
                          <strong>Role:</strong> {user.role}
                        </li>
                        <li className="list-group-item">
                          <strong>Status:</strong>{" "}
                          {user.status === 1 ? "Active" : "Inactive"}
                        </li>
                        <li className="list-group-item">
                          <strong>User ID:</strong> {user.user_id}
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </>
  );
}
