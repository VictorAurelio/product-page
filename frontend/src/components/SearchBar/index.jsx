import React, { useState, useEffect } from 'react';
import debounce from 'lodash.debounce';
import './styles.scss';

const SearchBar = ({ onSearch }) => {
  const [searchTerm, setSearchTerm] = useState('');

  const handleSearch = debounce((searchTerm) => {
    onSearch(searchTerm);
  }, 500);

  useEffect(() => {
    handleSearch(searchTerm);
  }, [searchTerm]);

  const handleChange = (event) => {
    setSearchTerm(event.target.value);
  };

  return (
    <div className="search-bar">
      <input
        type="text"
        value={searchTerm}
        onChange={handleChange}
        placeholder="Search..."
        aria-label="Search"
      />
    </div>
  );
};

export default SearchBar;