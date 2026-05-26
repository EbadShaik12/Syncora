import React from 'react';
import { createRoot } from 'react-dom/client';
import LandingPage from './Pages/LandingPage';

const rootElement = document.getElementById('react-landing-root');

if (rootElement) {
    const isAuthenticated = rootElement.getAttribute('data-authenticated') === 'true';
    const stats = JSON.parse(rootElement.getAttribute('data-stats') || '{}');
    const featuredStartups = JSON.parse(rootElement.getAttribute('data-featured-startups') || '[]');
    const root = createRoot(rootElement);
    root.render(
        <LandingPage 
            isAuthenticated={isAuthenticated} 
            stats={stats} 
            featuredStartups={featuredStartups} 
        />
    );
}
