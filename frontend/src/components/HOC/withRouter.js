import { useNavigate } from "react-router-dom";

function withRouter(Component) {
  function ComponentWithRouterProps(props) {
    let navigate = useNavigate();
    return <Component {...props} router={{ navigate }} />;
  }

  return ComponentWithRouterProps;
}

export default withRouter;
