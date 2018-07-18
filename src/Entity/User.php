<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="fuser")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Please enter your name.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Please enter your address.", groups={"Registration", "Profile"})
     */
    protected $address;

    /**
     * @ORM\Column(type="string", length=16)
     *
     * @Assert\NotBlank(message="Please enter your zip.", groups={"Registration", "Profile"})
     */
    protected $zip;

    /**
     * @ORM\Column(type="string", length=32)
     *
     * @Assert\NotBlank(message="Please enter your city.", groups={"Registration", "Profile"})
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=32)
     *
     * @Assert\NotBlank(message="Please enter your country.", groups={"Registration", "Profile"})
     */
    protected $country;

    /**
     * @ORM\Column(type="string", length=255, name="address_shipping")
     *
     * @Assert\NotBlank(message="Please enter your shipping address.", groups={"Registration", "Profile"})
     */
    protected $addressShipping;

    /**
     * @ORM\Column(type="string", length=16, name="zip_shipping")
     *
     * @Assert\NotBlank(message="Please enter your shipping zip.", groups={"Registration", "Profile"})
     */
    protected $zipShipping;

    /**
     * @ORM\Column(type="string", length=32, name="city_shipping")
     *
     * @Assert\NotBlank(message="Please enter your shipping city.", groups={"Registration", "Profile"})
     */
    protected $cityShipping;

    /**
     * @ORM\Column(type="string", length=32, name="country_shipping")
     *
     * @Assert\NotBlank(message="Please enter your shipping country.", groups={"Registration", "Profile"})
     */
    protected $countryShipping;

    /**
     * @ORM\Column(length=32, nullable=true)
     *
     */
    protected $ip;



    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Username is not needed
     *
     * @param string $email
     * @return $this|\FOS\UserBundle\Model\UserInterface
     */
    public function setEmail($email)
    {
        $this->setUsername($email);
        return parent::setEmail($email);
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set zip
     *
     * @param string $zip
     *
     * @return User
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set addressShipping
     *
     * @param string $addressShipping
     *
     * @return User
     */
    public function setAddressShipping($addressShipping)
    {
        $this->addressShipping = $addressShipping;

        return $this;
    }

    /**
     * Get addressShipping
     *
     * @return string
     */
    public function getAddressShipping()
    {
        return $this->addressShipping;
    }

    /**
     * Set zipShipping
     *
     * @param string $zipShipping
     *
     * @return User
     */
    public function setZipShipping($zipShipping)
    {
        $this->zipShipping = $zipShipping;

        return $this;
    }

    /**
     * Get zipShipping
     *
     * @return string
     */
    public function getZipShipping()
    {
        return $this->zipShipping;
    }

    /**
     * Set cityShipping
     *
     * @param string $cityShipping
     *
     * @return User
     */
    public function setCityShipping($cityShipping)
    {
        $this->cityShipping = $cityShipping;

        return $this;
    }

    /**
     * Get cityShipping
     *
     * @return string
     */
    public function getCityShipping()
    {
        return $this->cityShipping;
    }

    /**
     * Set countryShipping
     *
     * @param string $countryShipping
     *
     * @return User
     */
    public function setCountryShipping($countryShipping)
    {
        $this->countryShipping = $countryShipping;

        return $this;
    }

    /**
     * Get countryShipping
     *
     * @return string
     */
    public function getCountryShipping()
    {
        return $this->countryShipping;
    }

    /**
     * @param string $ip
     *
     * @return User
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }
}
