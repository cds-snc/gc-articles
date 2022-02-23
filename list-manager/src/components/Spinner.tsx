import * as React from 'react';

const styles: { [key: string]: React.CSSProperties } = {
  ldsEllipsis: {
    display: 'inline-block',
    position: 'relative',
    width: '80px',
    height: '80px',
  },
  'lds-ellipsis-div': {
    position: 'absolute',
    top: '33px',
    width: '13px',
    height: '13px',
    borderRadius: '50%',
    background: '#ccc',
    animationTimingFunction: 'cubic-bezier(0, 1, 1, 0)',
  },
  'lds-ellipsis-child-1': {
    left: '8px',
    animation: 'lds-ellipsis1 0.6s infinite',
  },
  'lds-ellipsis-child-2': {
    left: '8px',
    animation: 'lds-ellipsis2 0.6s infinite',
  },
  'lds-ellipsis-child-3': {
    left: '32px',
    animation: 'lds-ellipsis2 0.6s infinite',
  },
  'lds-ellipsis-child-4': {
    left: '56px',
    animation: 'lds-ellipsis3 0.6s infinite',
  },
};

const keyframes = `
  @keyframes lds-ellipsis1 {
    0%   { transform: scale(0);}
    100% { transform: scale(1); }
  }

  @keyframes lds-ellipsis3 {
      0%   { transform: scale(1); }
      100% { transform: scale(0); }
  }

  @keyframes lds-ellipsis2 {
      0%   { transform: translate(0, 0); }
      100% { transform: translate(24px, 0); }
  }
`;

export const Spinner = () => {
  return (
    <>
      <style>{keyframes}</style>
      <div style={{ display: 'flex', justifyContent: 'center' }}>
        <div style={styles.ldsEllipsis}>
          <div
            style={{
              ...styles['lds-ellipsis-div'],
              ...styles['lds-ellipsis-child-1'],
            }}
          ></div>
          <div
            style={{
              ...styles['lds-ellipsis-div'],
              ...styles['lds-ellipsis-child-2'],
            }}
          ></div>
          <div
            style={{
              ...styles['lds-ellipsis-div'],
              ...styles['lds-ellipsis-child-3'],
            }}
          ></div>
          <div
            style={{
              ...styles['lds-ellipsis-div'],
              ...styles['lds-ellipsis-child-4'],
            }}
          ></div>
        </div>
      </div>
    </>
  );
};