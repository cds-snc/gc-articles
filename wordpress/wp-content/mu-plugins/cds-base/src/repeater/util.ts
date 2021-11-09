export const swap = (arr, index1, index2) => {
    const a = { ...arr[index1] };
    const b = { ...arr[index2] };
  
    return arr.map((item, i) => {
      if (i === index1) {
        item = b;
      }
      if (i === index2) {
        item = a;
      }
  
      return item;
    });
  };