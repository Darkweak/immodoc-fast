import React from 'react';
import {
  HydraAdmin,
  ResourceGuesser,
  dataProvider as baseDataProvider,
  fetchHydra as baseFetchHydra
} from '@api-platform/admin';
import './app.css';
import { FilesCreate, FilesEdit, FilesShow } from './Resources';
import { parseHydraDocumentation } from '@api-platform/api-doc-parser/lib/hydra';
import authProvider from './authProvider';
import { Redirect } from 'react-router-dom';

const entrypoint = `${process.env.REACT_APP_API_ENTRYPOINT}/api`;
const fetchHeaders = {'Authorization': `Bearer ${window.localStorage.getItem('token')}`};
const fetchHydra = (url, options = {}) => baseFetchHydra(url, {
  ...options,
  headers: new Headers(fetchHeaders),
});
const apiDocumentationParser = entrypoint => parseHydraDocumentation(entrypoint, { headers: new Headers(fetchHeaders) })
  .then(
    ({ api }) => ({api}),
    (result) => {
      switch (result.status) {
        case 401:
          return Promise.resolve({
            api: result.api,
            customRoutes: [{
              props: {
                path: '/',
                render: () => <Redirect to={`/login`}/>,
              },
            }],
          });

        default:
          return Promise.reject(result);
      }
    },
  );
const dataProvider = baseDataProvider(entrypoint, fetchHydra, apiDocumentationParser);

export default () => <HydraAdmin {...{
    authProvider,
    apiDocumentationParser,
    dataProvider,
    entrypoint
  }}>
  <ResourceGuesser name="files" show={FilesShow} create={FilesCreate} edit={FilesEdit}/>
</HydraAdmin>;
