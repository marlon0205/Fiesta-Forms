## Basic Idea
We are creating a web-application that other companies aren't just using, but they want to have their own dedicated server with custom branding to run and administer their forms on.
The web-server would **presumabely** be hosted by us, but the company should have the ability to [[#Connecting to Databases of Companies|connect their own database]] to their instance of our product.

## Requirements
- [[Source Document|Pflichtenheft.pdf]]

### Roles
- **Admins:** Users that have the ability to create forms that *Guests* can answer
- **Customers:** Users that can fill out forms created by *Admins*
- **Guests:** Users that can see the results of forms, but they can neither create them, nor fill them out

### GUI
- Display poll-results graphically
- Search forms by name
- Filter forms by product-/service-category
- Display most recent forms (by creation date) on the homepage
- The following lists need to be available
	- All active forms
	- Archive of all expired forms
	- Statistics **(?)**

### Features
#### Rewards-Program
Create a, incentive for *Customers* to answer forms by handing out **Rewards** for the most active *Customers*. These rewards and conditions should be chosen by the *Customer*.

#### Connecting to Databases of Companies
- The *Customer company* already has a database of product-/service-categories
	- These can be sent as JSON to our service
- - The *Customer company* already has a database of its *customers*, and wants to be able to cross-reference out *customer*-data with their own
	**->** We need to be able to sent *customer*-data via JSON

- All relevant data includes:
	- Employe-data
	- *Customer*-data
	- Product-data
	- Service-data (as in services the *Customer-company* sells)
