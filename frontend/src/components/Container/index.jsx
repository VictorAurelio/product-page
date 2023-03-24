import React from 'react';

import './styles.scss';

const Container = ({ children }) => (
    <main className="app-container">
        {children}
    </main>
)

export default Container;