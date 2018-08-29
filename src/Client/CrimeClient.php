<?php

namespace BigPropertyData\Police\Client;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

class CrimeClient extends AbstractClient
{
    /**
     * Crimes at street-level; either within a 1 mile radius of a single point, or within a custom area.
     *
     * @see https://data.police.uk/docs/method/crime-street/
     *
     * @throws RequestException if the request fails
     *
     * @return mixed|null
     */
    public function streetCrime(array $location, \DateTime $date, $params = [])
    {
        return $this->streetCrimeAync($location, $date, $params)->wait();
    }

    /**
     * Crimes at street-level; either within a 1 mile radius of a single point, or within a custom area (asynchronous).
     *
     * @see https://data.police.uk/docs/method/crime-street/
     *
     * @param array|array[]|string $location
     * @param \DateTime $date
     * @param array $params
     * @return PromiseInterface
     */
    public function streetCrimeAync($location, \DateTime $date, array $params = [])
    {
        $params = $this->buildLocationParams($location, $params);

        $params['date'] = $date->format('Y-m');

        return $this->request('crimes-street/all-crime', 'get', $params);
    }

    /**
     * Outcomes at street-level; either at a specific location, within a 1 mile radius of a single point, or within a custom area.
     *
     * @see https://data.police.uk/docs/method/outcomes-at-location/
     *
     * @throws RequestException if the request fails
     *
     * @return mixed|null
     */
    public function streetOutcome(array $location, \DateTime $date, $params = [])
    {
        return $this->streetOutcomeAync($location, $date, $params)->wait();
    }

    /**
     * Outcomes at street-level; either at a specific location, within a 1 mile radius of a single point, or within a custom area (asynchronous).
     *
     * @see https://data.police.uk/docs/method/outcomes-at-location/
     *
     * @param array|array[]|string $location
     * @param \DateTime $date
     * @param array $params
     * @return PromiseInterface
     */
    public function streetOutcomeAync($location, \DateTime $date, array $params = [])
    {
        $params = $this->buildLocationParams($location, $params);

        $params['date'] = $date->format('Y-m');

        return $this->request('outcomes-at-location', 'get', $params);
    }

    /**
     * Returns just the crimes which occurred at the specified location, rather than those within a radius. If given
     * latitude and longitude, finds the nearest pre-defined location and returns the crimes which occurred there.
     *
     * @see https://data.police.uk/docs/method/crimes-at-location/
     *
     * @throws RequestException if the request fails
     *
     * @return mixed|null
     */
    public function crimesAtLocation(array $location, \DateTime $date, $params = [])
    {
        return $this->crimesAtLocationAync($location, $date, $params)->wait();
    }

    /**
     * Returns just the crimes which occurred at the specified location, rather than those within a radius. If given
     * latitude and longitude, finds the nearest pre-defined location and returns the crimes which occurred there (asynchronous).
     *
     * @see https://data.police.uk/docs/method/crimes-at-location/
     *
     * @param array|array[]|string $location
     * @param \DateTime $date
     * @param array $params
     * @return PromiseInterface
     */
    public function crimesAtLocationAync($location, \DateTime $date, array $params = [])
    {
        $params = $this->buildLocationParams($location, $params);

        $params['date'] = $date->format('Y-m');

        return $this->request('crimes-at-location', 'get', $params);
    }
}
