								// Display a message box for the user's choice
								let userChoice = confirm("Do you want to print using the browser's print dialog? (OK for browser print, Cancel for API print)");

								if(userChoice) {
									// User chose browser's print dialog
									printButton.parentNode.removeChild(printButton);
									window.print();
								} else {
									// User chose ePOS JS SDK
									printUsingEposSDK();
								}