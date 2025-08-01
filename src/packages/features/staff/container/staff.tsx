import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { SCREENS } from "../../screens";
import "../../staff/styles/staff.css";
import apiClient from "../../authClient";
import Header from "../../../../components/header";

export default function Staff() {
  const navigate = useNavigate();
  const [editMode, setEditMode] = useState(false);
  const [editingUserId, setEditingUserId] = useState<number | null>(null);
  const [currentUserId, setCurrentUserId] = useState<number | null>(null);
  const [isAuthorized, setIsAuthorized] = useState<boolean | null>(null);

  const [formData, setFormData] = useState({
    first_name: "",
    last_name: "",
    email: "",
    username: "",
    nickname: "",
    password: "",
    role_id: 2,
    status: 1,
    delete_user: 0,
    address: "",
    cellphone: "",
    homephone: "",
    location: 1,
    department: 1,
    jobrole: 5,
    last_login: "",
  });

  const resetForm = () => {
    setFormData({
      first_name: "",
      last_name: "",
      email: "",
      username: "",
      nickname: "",
      password: "",
      role_id: 2,
      status: 1,
      delete_user: 0,
      address: "",
      cellphone: "",
      homephone: "",
      location: 1,
      department: 1,
      jobrole: 5,
      last_login: "",
    });
    setEditMode(false);
    setEditingUserId(null);
    setShowModal(false);
  };

  const [message, setMessage] = useState<string | null>(null);
  const [messageType, setMessageType] = useState<"success" | "danger">("success");
  const [showModal, setShowModal] = useState(false);
  const [users, setUsers] = useState([]);

  const [searchFilters, setSearchFilters] = useState({
    keyword: "",
    location_id: "",
    department_id: "",
    job_role_id: "",
  });

  const [triggerFetch, setTriggerFetch] = useState(false);

  useEffect(() => {
    if (triggerFetch) {
      fetchUsers();
      setTriggerFetch(false); // reset trigger
    }
  }, [searchFilters]);


  const fetchUsers = async () => {
    console.log('fetch users');
    try {
      const params = new URLSearchParams();

      console.log('params 1', params.toString());
      console.log('searchFilters', searchFilters);

      if (searchFilters.keyword) params.append("keyword", searchFilters.keyword);
      if (searchFilters.location_id) params.append("location_id", searchFilters.location_id);
      if (searchFilters.department_id) params.append("department_id", searchFilters.department_id);
      if (searchFilters.job_role_id) params.append("job_role_id", searchFilters.job_role_id);
      console.log('params', params.toString());
      const res = await apiClient.get(`/get-users?${params.toString()}`, {
        withCredentials: true,
      });

      console.log('res', res.data);

      if (res.data.success) {
        setUsers(res.data.data);
      }
    } catch (err) {
      console.error("Error fetching users", err);
    }
  };

  // const defaultFilters = 

  const handleResetFilters = () => {
    console.log("Resetting filters");
    setSearchFilters({
      keyword: "",
      location_id: "",
      department_id: "", 
      job_role_id: "",
    });

    setTriggerFetch(true);
    // fetchUsers(); // Re-fetch with cleared filters
  };


  type Option = { id: number; name: string };

  const [departments, setDepartments] = useState<Option[]>([]);
  const [jobroles, setJobroles] = useState<Option[]>([]);
  const [locations, setLocations] = useState<Option[]>([]);
  const [metaLoading, setMetaLoading] = useState(true);

  const fetchMetaData = async () => {
    try {
      setMetaLoading(true);
      const res = await apiClient.get("/get-meta", { withCredentials: true });

      if (res?.data?.success && res?.data?.data) {
        const { departments = [], jobroles = [], locations = [] } = res.data.data;

        setDepartments(departments);
        setJobroles(jobroles);
        setLocations(locations);
      } else {
        console.warn("Unexpected response shape:", res?.data);
      }
    } catch (err) {
      console.error("Error fetching metadata", err);
    } finally {
      setMetaLoading(false);
    }
  };





  useEffect(() => {
    const checkAuth = async () => {
      try {
        const res = await apiClient.get("/me");
        if (res.data?.success === true) {
          const CurrentUserId = res.data?.data?.user_id;
          setCurrentUserId(CurrentUserId);
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

    fetchUsers();
    checkAuth();
    fetchMetaData();
  }, [navigate]);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: name === "role_id" || name === "status" || name === "location" || name === "department" || name === "jobrole" ? Number(value) : value }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const payload = {
        ...formData,
        created_by: currentUserId,
      };

      let res;
      if (editMode && editingUserId !== null) {
        res = await apiClient.put(`/users/${editingUserId}`, payload, { withCredentials: true });
      } else {
        res = await apiClient.post("/add-user", payload, { withCredentials: true });
      }

      const { success, message } = res.data;

      setMessage(message);
      setMessageType(success ? "success" : "danger");

      if (success) {
        resetForm();
        fetchUsers();
      } else {
        setShowModal(true);
      }

      setTimeout(() => setMessage(null), 4000);
    } catch (error: any) {
      setShowModal(false);
      setMessage(error?.response?.data?.message);
      setMessageType("danger");
      setTimeout(() => setMessage(null), 4000);
    }
  };

  if (isAuthorized === null) return <p>Loading...</p>;
  if (isAuthorized === false) return null;

  return (
    <>
      <Header active="Staff" />

      {message && (
        <div className="toast-wrapper">
          <div className={`custom-toast toast show text-white bg-${messageType} border-0`} role="alert">
            <div className="d-flex">
              <div className="toast-body">{message}</div>
              <button type="button" className="btn-close btn-close-white" aria-label="Close" onClick={() => setMessage(null)}></button>
            </div>
          </div>
        </div>
      )}



      <div className="dashboard-content">

        <div className="header-row d-flex justify-content-between align-items-center">
          <h2>Staff Management</h2>
          <button className="btn btn-primary blue-btn btn-success" onClick={() => setShowModal(true)}>Add New User</button>
        </div>

        <div className="search-container  mb-4 bg-white p-3 rounded shadow-sm">
          <div className="row">

            <div className="col-md-4">
              <div className="search-bar d-flex align-items-center">
                <input
                  type="text"
                  className="form-control"
                  placeholder="Search users..."
                  value={searchFilters.keyword}
                  onChange={(e) => setSearchFilters({ ...searchFilters, keyword: e.target.value })}
                />
              </div>
            </div>

            <div className="col-md-8">
              <div className="filter-options d-flex justify-content-end gap-2">
                <select
                  className="form-select"
                  value={searchFilters.location_id}
                  onChange={(e) => setSearchFilters({ ...searchFilters, location_id: e.target.value })}
                >
                  <option value="">Filter by Location</option>
                  {locations.map((location) => (
                    <option key={location.id} value={location.id}>{location.name}</option>
                  ))}
                </select>
                <select
                  className="form-select"
                  value={searchFilters.department_id}
                  onChange={(e) => setSearchFilters({ ...searchFilters, department_id: e.target.value })}
                >
                  <option value="">Filter by Department</option>
                  {departments.map((department) => (
                    <option key={department.id} value={department.id}>{department.name}</option>
                  ))}
                </select>
                <select
                  className="form-select"
                  value={searchFilters.job_role_id}
                  onChange={(e) => setSearchFilters({ ...searchFilters, job_role_id: e.target.value })}
                >
                  <option value="">Filter by Job Role</option>
                  {jobroles.map((jobrole) => (
                    <option key={jobrole.id} value={jobrole.id}>{jobrole.name}</option>
                  ))}
                </select>
                <button className="btn btn-primary blue-btn" onClick={fetchUsers}>
                  Search
                </button>
                <button className="btn btn-secondary" onClick={handleResetFilters}>
                  Reset
                </button>
              </div>
            </div>

          </div>
        </div>


        <div className="listing-area">
          <div className="table-responsive">
            <table className="table table-bordered table-striped">
              <thead className="table-light">
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Username</th>
                  <th>Nickname</th>
                  <th>Permission</th>
                  <th>Status</th>
                  <th>Location</th>
                  <th>Department</th>
                  <th>Job Role</th>
                  <th>Last Login</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                {users.length === 0 ? (
                  <tr><td colSpan={12} className="text-center">No users found.</td></tr>
                ) : (
                  users.map((user) => (
                    <tr key={user.id}>
                      <td>{user.id}</td>
                      <td>{user.first_name} {user.last_name}</td>
                      <td>{user.email}</td>
                      <td>{user.username}</td>
                      <td>{user.nickname || "N/A"}</td>
                      <td>{user.role}</td>
                      <td>{user.status === 1 ? "Activated" : "Not Activated"}</td>
                      <td>{user.location_name}</td>
                      <td>{user.department_name}</td>
                      <td>{user.jobrole_name}</td>
                      <td>{user.last_login || "N/A"}</td>
                      <td>
                        <button
                          className="btn btn-sm btn-success"
                          onClick={() => {
                            setEditMode(true);
                            setEditingUserId(user.id);
                            setFormData({
                              first_name: user.first_name,
                              last_name: user.last_name,
                              email: user.email,
                              username: user.username,
                              nickname: user.nickname || "",
                              password: "",
                              role_id: user.role_id,
                              status: user.status,
                              delete_user: 0,
                              address: user.address || "",
                              cellphone: user.cellphone || "",
                              homephone: user.homephone || "",
                              location: user.location || 1,
                              department: user.department || 1,
                              jobrole: user.jobrole || 5,
                              last_login: user.last_login || "",
                            });
                            setShowModal(true);
                          }}
                        >
                          Edit
                        </button>
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>
        </div>

        {/* Modal */}
        {showModal && (
          <>
            <div className="modal show d-block" tabIndex={-1}>
              <div className="modal-dialog modal-wrapper">
                <div className="modal-content">
                  <form onSubmit={handleSubmit}>
                    <div className="modal-header form-header">
                      <h5 className="modal-title">{editMode ? "Edit Existing User" : "Add New User"}</h5>
                      <button type="button" className="btn-close" onClick={resetForm}></button>
                    </div>
                    <div className="modal-body">
                      <div className="form-area">
                        <div className="row ">

                          <div className="col-md-6">
                            <label className="form-label">First Name*</label>
                            <input type="text" name="first_name" className="form-control" value={formData.first_name} onChange={handleChange} required />
                          </div>
                          <div className="col-md-6">
                            <label className="form-label">Last Name*</label>
                            <input type="text" name="last_name" className="form-control" value={formData.last_name} onChange={handleChange} required />
                          </div>

                          <div className="col-md-12">
                            <label className="form-label">Email*</label>
                            <input type="email" name="email" className="form-control" value={formData.email} onChange={handleChange} required />
                          </div>

                          <div className="col-md-12">
                            <label className="form-label">Username*</label>
                            <input type="text" name="username" className="form-control" value={formData.username} onChange={handleChange} required />
                          </div>

                          {/* //nickname */}
                          <div className="col-md-12">
                            <label className="form-label">Nickname</label>
                            <input type="text" name="nickname" className="form-control" value={formData.nickname} onChange={handleChange} />
                          </div>


                          <div className="col-md-6">
                            <label className="form-label">Role*</label>
                            <select name="role_id" className="form-select" value={formData.role_id} onChange={handleChange} required>
                              <option value={1}>Manager</option>
                              <option value={2}>Employee</option>
                            </select>
                          </div>

                          <div className="col-md-6">
                            <label className="form-label">Status*</label>
                            <select name="status" className="form-select" value={formData.status} onChange={handleChange} required>
                              <option value={1}>Activate</option>
                              <option value={0}>Deactivate</option>
                            </select>
                          </div>

                          <div className="col-md-6">
                            <label className="form-label">Cellphone</label>
                            <input type="tell" name="cellphone" className="form-control" value={formData.cellphone} onChange={handleChange} />
                          </div>
                          <div className="col-md-6">
                            <label className="form-label">Home Phone</label>
                            <input type="tell" name="homephone" className="form-control" value={formData.homephone} onChange={handleChange} />
                          </div>

                          <div className="col-md-12">
                            <label className="form-label">Address</label>
                            <textarea name="address" className="form-control" value={formData.address} onChange={handleChange} />
                          </div>


                          <div className="col-md-4">
                            <label className="form-label">Department</label>
                            <select
                              name="department"
                              className="form-select"
                              value={formData.department}
                              onChange={handleChange}
                              disabled={metaLoading || departments.length === 0}
                            >
                              <option value="" disabled>{metaLoading ? "Loading..." : "Select department"}</option>
                              {departments.map(d => (
                                <option key={d.id} value={d.id}>{d.name}</option>
                              ))}
                            </select>
                          </div>

                          <div className="col-md-4">
                            <label className="form-label">Job Role</label>
                            <select
                              name="jobrole"
                              className="form-select"
                              value={formData.jobrole}
                              onChange={handleChange}
                              disabled={metaLoading || jobroles.length === 0}
                            >
                              <option value="" disabled>{metaLoading ? "Loading..." : "Select job role"}</option>
                              {jobroles.map(r => (
                                <option key={r.id} value={r.id}>{r.name}</option>
                              ))}
                            </select>
                          </div>

                          <div className="col-md-4">
                            <label className="form-label">Location</label>
                            <select
                              name="location"
                              className="form-select"
                              value={formData.location}
                              onChange={handleChange}
                              disabled={metaLoading || locations.length === 0}
                            >
                              <option value="" disabled>{metaLoading ? "Loading..." : "Select location"}</option>
                              {locations.map(l => (
                                <option key={l.id} value={l.id}>{l.name}</option>
                              ))}
                            </select>
                          </div>


                          <div className="col-md-12">
                            <label className="form-label">Password{editMode ? formData.password || "" : "*"}</label>
                            <input
                              type="password"
                              name="password"
                              className="form-control"
                              value={formData.password}
                              onChange={handleChange}
                              required={!editMode}
                              placeholder={editMode ? "Leave blank to keep unchanged" : ""}
                            />
                          </div>


                        </div>
                      </div>
                    </div>
                    <div className="modal-footer">
                      <button type="button" className="btn btn-secondary" onClick={resetForm}>Cancel</button>
                      <button type="submit" className="btn btn-primary">Submit</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div className="modal-backdrop fade show"></div>
          </>
        )}
      </div>
    </>
  );
}
