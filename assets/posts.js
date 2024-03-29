ReactDOM.render(<LikeButton likes={initialLikes} isLiked={initialIsLiked} />, span);
console.log("Hello It's me Aya Bienvenue dans my Blog");
//React
//ReactDom

class LikeButton extends React.Component {
    state = {
             likes: this.props.likes || 0,
             isLiked: this.props.isLiked || false
            };

        handleClick = () => {
           const isLiked = this.state.isLiked;
           const likes = this.state.likes + (isLiked ? -1 : 1);

           this.setState({ likes, isLiked: !isLiked }); 
         }

       render(){
          return (
           <button className="btn btn-link" onClick={this.handleClick}>
           {this.state.likes} &nbsp;
           <i 
           className={
              this.state.isLiked ? "fas fa-thumbs-up" : "far fa-thumbs-up"} />
              &nbsp; 
           {this.state.isLiked ? "Je n'aime plus !" : "J'aime !"}
          </button>
          );
          
        }
    
}

document.querySelectorAll('span.react-like').forEach(function(span){
  ReactDOM.render(React.createElement(LikeButton),span);

});