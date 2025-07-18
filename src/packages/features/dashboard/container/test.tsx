import React, { useEffect, useState } from "react";
import apiClient from "../../authClient"; // assumed to be a pre-configured Axios instance

const VerifyStatus = () => {
  const [verified, setVerified] = useState<boolean | null>(null);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    apiClient
      .get("/me")
      .then((res) => {
        if (res.data?.success === true) {
          setVerified(true);
        } else {
          setVerified(false);
        }
      })
      .catch((err) => {
        console.error(err);
        setError("Token verification failed.");
        setVerified(false);
      });
  }, []);

  if (error) return <p>{error}</p>;
  if (verified === null) return <p>Loading...</p>;

  return (
    <div>
      <h2>User is {verified ? "Verified" : "Not Verified"}</h2>
    </div>
  );
};

export default VerifyStatus;
