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

    const result = dispatch(await loginUser(email, password));

    if (result.payload.success && result.payload.token) {
      navigate(SCREENS.Dashboard);
    } else {
      alert("Login failed. Please check your credentials.");
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
