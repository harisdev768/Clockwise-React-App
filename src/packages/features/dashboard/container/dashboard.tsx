import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { SCREENS } from "../../screens";
import "./dashboard.css";
import apiClient from "../../authClient";
import Header from "../../../../components/header";
import Footer from "../../../../components/Footer";

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
  const [clockStatus, setClockStatus] = useState<boolean>(false);
  const [breakStatus, setBreakStatus] = useState<boolean>(false);
  const [clockId, setClockId] = useState<number | null>(null);
  const [loadingClock, setLoadingClock] = useState(false);
  const [totalShift, setTotalShift] = useState("00:00");
  const [timeline, setTimeline] = useState<any[]>([]);
  const [noteInput, setNoteInput] = useState("");

  const fetchClockStatus = async () => {
    if (!user) return;
    try {
      const res = await apiClient.get(`/clock/status?user_id=${user.user_id}`);
      if (res.data?.success === true) {
        const data = res.data.data;
        setClockStatus(data.clockStatus);
        setBreakStatus(data.breakStatus);
        setClockId(data.meta.clock_id);

        // ✅ Check if clock_in_at exists before calculating
        if (!data.meta.clock_in_at) {
          setTotalShift("00:00");
        } else {
          const start = new Date(data.meta.clock_in_at);
          const end = data.meta.clock_out_at ? new Date(data.meta.clock_out_at) : new Date();
          setTotalShift(getDuration(start, end));
        }

        const events: any[] = [];

        // Clock In event
        events.push({
          type: "clock",
          label: "Clocked in.",
          time: data.meta.clock_in_at
        });

        // Notes
        if (Array.isArray(data.notes) && data.notes.length > 0) {
          data.notes.forEach((note: any) => {
            events.push({
              type: "note",
              label: note.note,
              time: note.note_at
            });
          });
        }

        // Breaks
        if (Array.isArray(data.breaks) && data.breaks.length > 0) {
          data.breaks.forEach((b: any) => {
            const start = new Date(b.break_started_at);
            const end = new Date(b.break_ended_at);
            const duration = (end.getTime() - start.getTime()) / 1000;
            events.push({
              type: "break",
              label: `Break ${duration} sec (${start.toLocaleTimeString()} - ${end.toLocaleTimeString()})`,
              time: b.break_started_at
            });
          });
        }

        // ✅ Sort events by time ascending
        events.sort((a, b) => new Date(a.time).getTime() - new Date(b.time).getTime());

        setTimeline(events);
      }
    } catch (err) {
      console.error("Failed to fetch clock status", err);
    }
  };


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
    if (user) fetchClockStatus();
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
      console.log("Clock action:", action);
      const res = await apiClient.post("/clock", {
        user_id: user.user_id,
        action,
      });
      console.log("Clock response:", res.data);
      if (res.data?.success === true) {
        console.log("Clock action successful");
        showToast(res.data.message);
        fetchClockStatus();
      }
    } catch (err) {
      console.error('error', err);
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
        showToast(res.data.message);
        fetchClockStatus();
      }
    } catch (err) {
      console.error(err);
      showToast("Something went wrong!");
    } finally {
      setLoadingClock(false);
    }
  };

  const handleAddNote = async () => {
    if (!user || !clockId || !noteInput.trim()) return;
    try {
      const res = await apiClient.post("/add-note", {
        user_id: user.user_id,
        clock_id: clockId,
        note: noteInput.trim(),
      });
      if (res.data?.success) {
        setNoteInput("");
        showToast("Note added successfully.");
        fetchClockStatus();
      } else {
        showToast(res.data?.message || "Failed to add note.");
      }
    } catch (err: any) {
      console.error(err);
      showToast(err.response?.data?.message || "Failed to add note.");
    }
  };

  const getDuration = (start: Date, end: Date) => {
    const ms = end.getTime() - start.getTime();
    const totalMinutes = Math.floor(ms / 60000);
    const hours = Math.floor(totalMinutes / 60);
    const minutes = totalMinutes % 60;
    return `${String(hours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}`;
  };

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
          <div className="row">
            <div className="col-md-4 p-0">
              <div className="card mt-4 ">
                <div className="card-body p-0">
                  <h5 className="card-title text-success"><i class="fa-solid fa-clock text-success"></i>Time Clock</h5>
                  <div className="current-time">
                    <p className="text-muted">
                      {new Date().toLocaleDateString()} - {new Date().toLocaleTimeString()}
                    </p>
                    <p><strong>Total Hours:</strong> {totalShift} hours.</p>
                  </div>
                  <div className="timeline">
                    {timeline.map((entry, idx) => (
                      <div className="d-flex align-items-center mb-2" key={idx}>
                        <span className="me-2">
                          {entry.type === "clock" && <i className="fa-solid fa-circle text-success"></i>}
                          {entry.type === "note" && <i className="fas fa-sticky-note"></i>}
                          {entry.type === "break" && <i className="fas fa-coffee"></i>}
                        </span>
                        <div className="flex-grow-1 bg-light p-2 rounded">
                          {entry.label}
                        </div>
                        <span className="ms-2 text-muted">
                          {new Date(entry.time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                        </span>
                      </div>
                    ))}
                  </div>
                  {clockStatus && !breakStatus && (
                    <div className="d-flex">
                      <input
                        className="form-control me-2"
                        value={noteInput}
                        onChange={(e) => setNoteInput(e.target.value)}
                        placeholder="Click to add notes"
                        disabled={breakStatus} // ✅ Disable while on break
                      />
                      <button
                        onClick={handleAddNote}
                        className="btn btn-outline-primary"
                        disabled={breakStatus} // ✅ Disable while on break
                      >
                        💬
                      </button>
                    </div>
                  )}
                  <div className="mt-3">
                    {!clockStatus && (
                      <button onClick={handleClock} className="btn btn-success" disabled={loadingClock}>
                        {loadingClock ? "Please wait..." : "Clock In"}
                      </button>
                    )}
                    {clockStatus && !breakStatus && (
                      <>
                        <button onClick={() => handleBreak("start")} className="btn btn-secondary ms-2" disabled={loadingClock}>
                          Start Break
                        </button>
                        <button onClick={handleClock} className="btn btn-danger" disabled={loadingClock}>
                          {loadingClock ? "Please wait..." : "Clock Out"}
                        </button>
                      </>
                    )}
                    {clockStatus && breakStatus && (
                      <button onClick={() => handleBreak("end")} className="btn btn-warning" disabled={loadingClock}>
                        {loadingClock ? "Please wait..." : "End Break"}
                      </button>
                    )}
                  </div>
                </div>
              </div>
            </div>
            {/* USER INFO */}
            <div className="col-md-8">
              <div className="card mt-4 ">
                <div className="card-body p-0 ">
                  <h5 className="card-title text-success"><i class="fa-regular fa-address-book text-success"></i>User Information</h5>
                  <div className="card-inner">
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

          </div> {/*   row */}
        </div>
      </div>
      {/* <Footer /> */}
    </>
  );
}
