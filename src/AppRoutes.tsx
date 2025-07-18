// AppRoutes.tsx
import { useEffect } from "react";
import { useDispatch } from "react-redux";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import Login from "./packages/features/Login/container/LoginScreen.tsx";
import Dashboard from "./packages/features/dashboard/container/dashboard.tsx";
import { fetchCurrentUser } from "./packages/features/redux/actions/auth.ts";
import ResetPassword from './packages/features/resetpassword/container/ResetPassword.tsx';
import ForgotPassword from './packages/features/resetpassword/container/ForgotPassword';
import VerifyStatus from "./packages/features/dashboard/container/test.tsx";




const AppRoutes = () => {
  const dispatch = useDispatch();

  useEffect(() => {
    dispatch(fetchCurrentUser());
  }, [dispatch]);

  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Login />} />
        <Route path="/dashboard" element={<Dashboard />} />
        <Route path="/reset-password" element={<ResetPassword />} />
        <Route path="/forgot-password" element={<ForgotPassword />} />
        <Route path="/test" element={<VerifyStatus />} />
      </Routes>
    </BrowserRouter>
  );
};

export default AppRoutes;



