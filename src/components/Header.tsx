import apiClient from "../packages/features/authClient";
import "../../src/components/styles/Header.css";

interface HeaderProps {
  active?: string; // like "Dashboard", "Staff", etc.
}

export default function Header({ active = "" }: HeaderProps) {
  const navLinks = [
    { href: "/dashboard", icon: "fa-gauge-high", label: "Dashboard" },
    { href: "/", icon: "fa-calendar-days", label: "ShiftPlanning" },
    { href: "/", icon: "fa-clock", label: "Time Clock" },
    { href: "/", icon: "fa-plane-departure", label: "Leave" },
    { href: "/", icon: "fa-graduation-cap", label: "Training" },
    { href: "/staff", icon: "fa-users", label: "Staff" },
    { href: "/", icon: "fa-chart-line", label: "Reports" },
    { href: "/", icon: "fa-people-group", label: "Group Accounts" },
  ];

  const handleLogout = () => {
    apiClient.post("/logout").then(() => {
      localStorage.removeItem("accessToken");
      localStorage.removeItem("accessTokenExpiry");
      window.location.href = "/";
    });
  };

  return (
    <div className="dashboard-topbar">
      <img
        src="/logo.png"
        className="h-logo"
        alt="Clockwise Logo"
      />
      <nav className="dashboard-nav">
        {navLinks.map((link) => (
          <a
            key={link.label}
            href={link.href}
            className={active === link.label ? "active" : ""}
          >
            <i className={`fa-solid ${link.icon}`}></i>
            {link.label}
          </a>
        ))}
        <button className="logout" onClick={handleLogout}>
          <i className="fa-solid fa-right-from-bracket"></i> Logout
        </button>
      </nav>
    </div>
  );
}