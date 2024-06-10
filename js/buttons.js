function CreateButtons(className, isNew) {
    this.className = className,
    this.isNew = isNew;
  };
  CreateButtons.prototype.createButton = function() {
    return (
      `
        <button class=${this.className}><span>Button</span></button>
        <p>${this.className}</p>
        ${this.isNew ? `<em>New</em>` : ''}
      `
    )
  }
  
  const bgHide = new CreateButtons('bg-hide');
  const bgShow = new CreateButtons('bg-show');
  const bgText = new CreateButtons('bg-text');
  const bgTextShowBg = new CreateButtons('bg-text-show-bg');
  const bgTextShowBorder = new CreateButtons('bg-text-show-border');
  const bgBorder = new CreateButtons('bg-border');
  const radialOut = new CreateButtons('radial-out');
  const radialIn = new CreateButtons('radial-in');
  const slideDown = new CreateButtons('slide-down');
  const slideUp = new CreateButtons('slide-up');
  const slideLeft = new CreateButtons('slide-left');
  const slideRight = new CreateButtons('slide-right');
  const radiusIncrease = new CreateButtons('radius-increase');
  const radiusDecrease = new CreateButtons('radius-decrease');
  const borderDashed = new CreateButtons('border-dashed');
  const borderDotted = new CreateButtons('border-dotted');
  const opacityIncrease = new CreateButtons('opacity-increase');
  const opacityDecrease = new CreateButtons('opacity-decrease');
  const grayscaleIncrease = new CreateButtons('grayscale-increase');
  const grayscaleDecrease = new CreateButtons('grayscale-decrease');
  const buttonBlur = new CreateButtons('button-blur');
  const textBlur = new CreateButtons('text-blur');
  const bgColor = new CreateButtons('bg-color');
  const textColor = new CreateButtons('text-color');
  const borderColor = new CreateButtons('border-color');
  const borderTextColor = new CreateButtons('border-text-color');
  const shadowIncrease = new CreateButtons('shadow-increase');
  const shadowDecrease = new CreateButtons('shadow-decrease');
  const shadowFloat = new CreateButtons('shadow-float');
  const scaleIncrease = new CreateButtons('scale-increase');
  const scaleDecrease = new CreateButtons('scale-decrease');
  const floatDown = new CreateButtons('float-down');
  const floatUp = new CreateButtons('float-up');
  const floatLeft = new CreateButtons('float-left');
  const floatRight = new CreateButtons('float-right');
  const buttonRotate = new CreateButtons('button-rotate');
  const buttonRotateGrow = new CreateButtons('button-rotate-grow');
  const buttonRotateShrink = new CreateButtons('button-rotate-shrink');
  const skewH = new CreateButtons('skew-horizontal');
  const skewV = new CreateButtons('skew-vertical');
  const flipH = new CreateButtons('flip-horizontal', true);
  const flipV = new CreateButtons('flip-vertical', true);
  const increaseLetterSpace = new CreateButtons('increase-letter-space', true);
  const swipeOverlay = new CreateButtons('swipe-overlay', true);
  const swipeOverlayOut = new CreateButtons('swipe-overlay-out', true);
  const iconSlideLeft = new CreateButtons('icon-slide-left', true);
  const iconSlideRight = new CreateButtons('icon-slide-right', true);
  
  const buttonsArray = [
    iconSlideLeft,
    iconSlideRight,
    swipeOverlayOut,
    swipeOverlay,
    increaseLetterSpace,
    flipH,
    flipV,
    bgHide, 
    bgShow, 
    bgText, 
    bgTextShowBg,
    bgTextShowBorder,
    radialOut, 
    radialIn, 
    slideDown, 
    slideUp, 
    slideLeft, 
    slideRight, 
    radiusIncrease, 
    radiusDecrease, 
    borderDashed,
    borderDotted,
    opacityIncrease,
    opacityDecrease,
    grayscaleIncrease,
    grayscaleDecrease,
    buttonBlur,
    textBlur,
    bgColor,
    textColor,
    borderColor,
    borderTextColor,
    shadowIncrease,
    shadowDecrease,
    shadowFloat,
    scaleIncrease,
    scaleDecrease,
    floatDown,
    floatUp,
    floatLeft,
    floatRight,
    buttonRotate,
    buttonRotateGrow,
    buttonRotateShrink,
    skewH,
    skewV,
  ];
  
  const buttonsWrapper = document.querySelector('.wrap');
  buttonsWrapper.innerHTML = 
    buttonsArray.map(button => `<div>${button.createButton()}</div>`).join('');