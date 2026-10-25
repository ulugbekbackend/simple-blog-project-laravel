// Label + input/textarea/select + validatsiya xatosi
export default function Field({ label, error, as: Tag = 'input', children, ...props }) {
    return (
        <label className="block">
            <span className="mb-1 block text-sm font-medium text-gray-700">{label}</span>
            <Tag
                {...props}
                className="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:outline-none"
            >
                {children}
            </Tag>
            {error && <span className="mt-1 block text-sm text-red-600">{error[0]}</span>}
        </label>
    );
}
