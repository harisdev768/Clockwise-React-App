import { combineReducers } from "redux";
import { configureStore } from "@reduxjs/toolkit";
import { useSession } from "./reducers/auth";
const rootReducer = combineReducers({
  user: useSession,
});
const store = configureStore({ reducer: rootReducer });

export type RootState = ReturnType<typeof rootReducer>;
export default store;
