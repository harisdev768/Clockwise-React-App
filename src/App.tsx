// App.tsx
import "./App.css";
import { Provider } from "react-redux";
import store from "./packages/features/redux/store";
import AppRoutes from "./AppRoutes"; // ⬅️ New component for routing + logic

function App() {
  return (
    <Provider store={store}>
      <AppRoutes />
    </Provider>
  );
}

export default App;
