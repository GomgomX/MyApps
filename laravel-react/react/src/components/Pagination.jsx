import React from 'react';

const Pagination = ({ currentPage, totalPages, onPageChange }) => {
  const pages = [];

  const startPage = Math.max(1, currentPage - 1);
  const endPage = Math.min(totalPages, currentPage + 1);

  if (startPage > 1) {
    pages.push(1);
    if (startPage > 2) {
      pages.push('...')
    }
  }

  for (let i = startPage; i <= endPage; i++) {
    pages.push(i);
  }

  if (endPage < totalPages) {
    if (endPage < totalPages - 1) {
      pages.push('...');
    }
    pages.push(totalPages);
  }

  return (
    <div style={{ display: 'flex', justifyContent: 'center', gap: '5px' }}>
      <button className="btn-paginate"
        onClick={() => onPageChange(currentPage - 1)}
        disabled={currentPage === 1}
      >
        Previous
      </button>

      {pages.map((page, index) => (
        typeof page === 'number' ? (
          <button className="btn-paginate"
            key={index}
            onClick={() => onPageChange(page)}
            disabled={page === currentPage}
          >
            {page}
          </button>
        ) : (
          <button key={index} className="btn-paginate"
            disabled={true}
          >
            ...
          </button>
        )
      ))}

      <button className="btn-paginate"
        onClick={() => onPageChange(currentPage + 1)}
        disabled={currentPage === totalPages}
      >
        Next
      </button>
    </div>
  );
};

export default Pagination;
