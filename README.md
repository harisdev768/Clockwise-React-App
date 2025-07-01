## 🧪 API Integration (React Example)

Use this sample fetch request to connect your React frontend to the login API:

fetch("http://clockwise.local/index.php/login", {
  method: "POST",
  headers: {
    "Content-Type": "application/json"
  },
  body: JSON.stringify({
    email: "haris@example.com",
    password: "123456"
  })
})
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      console.log("Login successful!", data);
    } else {
      console.log("Login failed:", data.message);
    }
  })
  .catch(err => console.error("API error:", err));
