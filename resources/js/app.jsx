import React from 'react';
import { createRoot } from 'react-dom/client';
import LandingPage from './Pages/LandingPage';

const rootElement = document.getElementById('react-landing-root');

if (rootElement) {
    const root = createRoot(rootElement);
    root.render(<LandingPage />);
}
