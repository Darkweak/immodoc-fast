import React from 'react';
import { FieldGuesser, ShowGuesser } from '@api-platform/admin';

export const getToken = () => localStorage.getItem('token');

export const Show = ({ fields, ...rest }) => (
  <ShowGuesser {...rest}>
    {
      fields.map((field, index) => <FieldGuesser key={index} source={field} addLabel />)
    }
  </ShowGuesser>
);
