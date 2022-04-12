export const capitalize = (s: string) => s && s[0].toUpperCase() + s.slice(1);

export const getListType = (type: string) => {
  //@todo --- temporary... note we're using language here -> en=email, fr=phone 
  if (type === 'en') {
    return 'email';
  }
  return 'phone';
};
