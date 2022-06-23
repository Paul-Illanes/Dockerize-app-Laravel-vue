import axios from '@axios'

export const parameter_pluck = function(varia) {
  axios.get('/api/auth/parameter/' + varia)
  .then((response) => {
      return response.data.data
  })
  .catch((error) => {
      console.log(error);
  });
}