import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { SCREENS } from "../../screens";

import { useDispatch } from "react-redux";
import { loginUser } from "../../redux/actions/auth";

function useLoginScreen() {
  const dispatch = useDispatch();
  
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const navigate = useNavigate();

  const handleNavigate = () => {
    navigate(SCREENS.Dashboard);
  };
  const handleLogin = async (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();

      const result = await dispatch(loginUser(email, password));
      console.log("Login result:", result);
      if (result.payload.success && result.payload.token) {
        navigate(SCREENS.Dashboard);
      }else {
        // Create toast container if it doesn't exist
        if (!document.getElementById("toast-container")) {
          const container = document.createElement("div");
          container.id = "toast-container";
          container.className = "toast-container position-fixed top-0 start-50 translate-middle-x p-3 z-3";
          document.body.appendChild(container);
        }

        // Create the toast element
        const toast = document.createElement("div");
        toast.className = "toast align-items-center text-bg-danger border-0 show w-auto";
        toast.setAttribute("role", "alert");
        toast.setAttribute("aria-live", "assertive");
        toast.setAttribute("aria-atomic", "true");

        toast.innerHTML = `
          <div class="d-flex" >
            <div class="toast-body">
              ${result.payload.message || "Login failed. Please try again."}
            </div>
            <button type="button" class="btn-close btn-close-white mx-0 my-auto pe-4" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        `;

        // Append and show the toast
        document.getElementById("toast-container").appendChild(toast);

        // Optional: auto-remove after 3 seconds
        setTimeout(() => toast.remove(), 3000);

        console.error("Login failed:", result.payload.message);
      }


    
  };


  return {
    setEmail,
    setPassword,
    handleLogin,
    handleNavigate,
  };
}

export default useLoginScreen;
