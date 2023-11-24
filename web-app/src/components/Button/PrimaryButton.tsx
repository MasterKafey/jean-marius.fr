import React from 'react';

interface PrimaryButtonProps {
    onClick?: () => void;
    label: string;
    className?: string;
}

const PrimaryButton: React.FC<PrimaryButtonProps> = ({ onClick, label, className }) => {
    return (
        <button
            onClick={onClick}
            className={`bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 ${className}`}
        >
            {label}
        </button>
    );
}

export default PrimaryButton;