import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import Field from '../components/Field.jsx';
import { useAuth } from '../auth.jsx';

export default function Register() {
    const { register } = useAuth();
    const navigate = useNavigate();
    const [form, setForm] = useState({ name: '', email: '', password: '', password_confirmation: '' });
    const [errors, setErrors] = useState({});

    const bind = (key) => ({
        value: form[key],
        error: errors[key],
        onChange: (e) => setForm({ ...form, [key]: e.target.value }),
    });

    async function submit(e) {
        e.preventDefault();
        try {
            await register(form);
            navigate('/posts');
        } catch (err) {
            setErrors(err.errors);
        }
    }

    return (
        <form onSubmit={submit} className="mx-auto max-w-md space-y-4 rounded bg-white p-6 shadow">
            <h1 className="text-2xl font-bold">Ro'yxatdan o'tish</h1>
            <Field label="Ism" {...bind('name')} />
            <Field label="Email" type="email" {...bind('email')} />
            <Field label="Parol" type="password" {...bind('password')} />
            <Field label="Parolni tasdiqlang" type="password" {...bind('password_confirmation')} />
            <button className="rounded bg-indigo-600 px-4 py-2 text-white">Ro'yxatdan o'tish</button>
        </form>
    );
}
