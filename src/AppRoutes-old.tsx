// AppRoutes.tsx
import { useEffect } from "react";
import { useDispatch } from "react-redux";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import Login from "./packages/features/Login/container/LoginScreen.tsx";
import Dashboard from "./packages/features/dashboard/container/dashboard.tsx";
import { fetchCurrentUser } from "./packages/features/redux/actions/auth.ts";

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
      </Routes>
    </BrowserRouter>
  );
};

export default AppRoutes;
