// components/EditUserForm.tsx
import React from "react";

interface EditUserFormProps {
  formData: any;
  onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
}

export default function EditUserForm({ formData, onChange }: EditUserFormProps) {
  return (
    <>
      <div className="row mb-3">
        <div className="col">
          <label className="form-label">First Name</label>
          <input type="text" name="first_name" className="form-control" value={formData.first_name} onChange={onChange} required />
        </div>
        <div className="col">
          <label className="form-label">Last Name</label>
          <input type="text" name="last_name" className="form-control" value={formData.last_name} onChange={onChange} required />
        </div>
      </div>
      <div className="mb-3">
        <label className="form-label">Email</label>
        <input type="email" name="email" className="form-control" value={formData.email} onChange={onChange} required />
      </div>
      <div className="mb-3">
        <label className="form-label">Username</label>
        <input type="text" name="username" className="form-control" value={formData.username} onChange={onChange} required />
      </div>
      {/* Password field is optional in edit */}
      <div className="mb-3">
        <label className="form-label">Password (leave blank to keep unchanged)</label>
        <input type="password" name="password" className="form-control" value={formData.password} onChange={onChange} />
      </div>
    </>
  );
}
