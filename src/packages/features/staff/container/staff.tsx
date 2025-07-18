import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { SCREENS } from "../../screens";
import "../../staff/styles/staff.css";
import apiClient from "../../authClient";
import Header from "../../../../components/header";

export default function Staff() {
  const navigate = useNavigate();
  const [isAuthorized, setIsAuthorized] = useState<boolean | null>(null);
  const [formData, setFormData] = useState({
    first_name: "",
    last_name: "",
    email: "",
    username: "",
    password: "",
  });

  const [message, setMessage] = useState<string | null>(null);
  const [messageType, setMessageType] = useState<"success" | "danger">("success");
  const [showModal, setShowModal] = useState(false);

  useEffect(() => {
    const checkAuth = async () => {
      try {
        const res = await apiClient.get("/me");
        if (res.data?.success === true) {
          const role = res.data?.data?.role;
          if (role === "Manager") {
            setIsAuthorized(true);
          } else {
            navigate(SCREENS.Dashboard);
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

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const res = await apiClient.post("/add-user", formData);
      const { success, message } = res.data;

      setMessage(message);
      setMessageType(success ? "success" : "danger");

      if (success) {
        setFormData({
          first_name: "",
          last_name: "",
          email: "",
          username: "",
          password: "",
        });
        setShowModal(false); // close modal on success
      }

      setTimeout(() => setMessage(null), 5000);
    } catch (error: any) {
      setShowModal(false);
      setMessage("Something went wrong.");
      setMessageType("danger");
      setTimeout(() => setMessage(null), 5000);
    }
  };

  if (isAuthorized === null) return <p>Loading...</p>;
  if (isAuthorized === false) return null;

  return (
    <>
      <Header active="Staff" />
      <div className="dashboard-content">
        <div className="header-row d-flex justify-content-between align-items-center">
          <h2>Staff Management</h2>
          <button className="btn btn-primary blue-btn" onClick={() => setShowModal(true)}>
            Add Employee
          </button>
        </div>

        <div className="user-info mb-4">
          <p><strong>✅ Welcome Manager! You can manage staff here.</strong></p>
        </div>

        {message && (
          <div className={`alert alert-${messageType}`} role="alert">
            {message}
          </div>
        )}

        {/* Modal */}
        {showModal && (
          <div className="modal show d-block" tabIndex={-1}>
            <div className="modal-dialog">
              <div className="modal-content">
                <form onSubmit={handleSubmit}>
                  <div className="modal-header">
                    <h5 className="modal-title">Add New Employee</h5>
                    <button
                      type="button"
                      className="btn-close"
                      onClick={() => setShowModal(false)}
                    ></button>
                  </div>
                  <div className="modal-body">
                    <div className="row mb-3">
                      <div className="col">
                        <label className="form-label">First Name</label>
                        <input
                          type="text"
                          name="first_name"
                          className="form-control"
                          value={formData.first_name}
                          onChange={handleChange}
                          required
                        />
                      </div>
                      <div className="col">
                        <label className="form-label">Last Name</label>
                        <input
                          type="text"
                          name="last_name"
                          className="form-control"
                          value={formData.last_name}
                          onChange={handleChange}
                          required
                        />
                      </div>
                    </div>

                    <div className="mb-3">
                      <label className="form-label">Email</label>
                      <input
                        type="email"
                        name="email"
                        className="form-control"
                        value={formData.email}
                        onChange={handleChange}
                        required
                      />
                    </div>

                    <div className="mb-3">
                      <label className="form-label">Username</label>
                      <input
                        type="text"
                        name="username"
                        className="form-control"
                        value={formData.username}
                        onChange={handleChange}
                        required
                      />
                    </div>

                    <div className="mb-3">
                      <label className="form-label">Password</label>
                      <input
                        type="password"
                        name="password"
                        className="form-control"
                        value={formData.password}
                        onChange={handleChange}
                        required
                      />
                    </div>
                  </div>
                  <div className="modal-footer">
                    <button type="button" className="btn btn-secondary" onClick={() => setShowModal(false)}>
                      Cancel
                    </button>
                    <button type="submit" className="btn btn-primary">
                      Submit
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        )}

        {/* Modal Backdrop */}
        {showModal && <div className="modal-backdrop fade show"></div>}
      </div>
    </>
  );
}
