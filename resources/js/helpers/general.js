export const getCurrentMonthNumber = () => {
  let monthNumber = `0${(new Date(Date.now()).getMonth() + 1)}`;
  if  (monthNumber > 2) {
    monthNumber = monthNumber.slice(1);
  }
  return monthNumber;
};
